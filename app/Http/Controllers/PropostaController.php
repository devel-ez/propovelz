<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropostaRequest;
use App\Models\Cliente;
use App\Models\Proposta;
use App\Models\PropostaItem;
use App\Models\PropostaModelo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropostaController extends Controller
{
    private const TENANT_ID = 1;

    private const STATUSES = [
        'rascunho'    => ['label' => 'Rascunho',    'color' => 'gray'],
        'enviada'     => ['label' => 'Enviada',     'color' => 'blue'],
        'visualizada' => ['label' => 'Visualizada', 'color' => 'purple'],
        'aprovada'    => ['label' => 'Aprovada',    'color' => 'green'],
        'recusada'    => ['label' => 'Recusada',    'color' => 'red'],
        'cancelada'   => ['label' => 'Cancelada',   'color' => 'orange'],
    ];

    /* ------------------------------------------------------------------ */
    /*  Listing                                                             */
    /* ------------------------------------------------------------------ */

    public function index(Request $request): View
    {
        $query = Proposta::with('cliente')
            ->where('tenant_id', self::TENANT_ID)
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nome', 'like', "%{$search}%"));
            });
        }

        $propostas = $query->paginate(15)->withQueryString();

        return view('propostas.index', [
            'propostas'     => $propostas,
            'statuses'      => self::STATUSES,
            'currentStatus' => $request->status,
            'search'        => $request->search,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Create / Store                                                      */
    /* ------------------------------------------------------------------ */

    public function create(): View
    {
        $clientes = Cliente::where('tenant_id', self::TENANT_ID)->orderBy('nome')->get();
        $modelos  = PropostaModelo::orderBy('titulo')->get(['id', 'titulo']);

        return view('propostas.create', [
            'clientes' => $clientes,
            'statuses' => self::STATUSES,
            'modelos'  => $modelos,
        ]);
    }

    public function store(StorePropostaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['tenant_id'] = self::TENANT_ID;
        $validated['status']    = $validated['status'] ?? 'rascunho';

        // Calcular valor_total a partir dos itens
        $itens = $request->input('itens', []);
        $total = collect($itens)->sum(fn($i) => (float)($i['valor_unitario'] ?? 0) * (float)($i['quantidade'] ?? 1));
        $validated['valor_total'] = $total;

        $proposta = Proposta::create($validated);
        $this->syncItens($proposta, $itens);

        return redirect()->route('propostas.index')
            ->with('success', 'Proposta criada com sucesso!');
    }

    /* ------------------------------------------------------------------ */
    /*  Show (internal preview)                                             */
    /* ------------------------------------------------------------------ */

    public function show(Proposta $proposta): View
    {
        $proposta->load('cliente', 'itens');

        return view('propostas.show', [
            'proposta' => $proposta,
            'statuses' => self::STATUSES,
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  Edit / Update                                                       */
    /* ------------------------------------------------------------------ */

    public function edit(Proposta $proposta): View
    {
        $proposta->load('itens');
        $clientes = Cliente::where('tenant_id', self::TENANT_ID)->orderBy('nome')->get();
        $modelos  = PropostaModelo::orderBy('titulo')->get(['id', 'titulo']);

        return view('propostas.edit', [
            'proposta' => $proposta,
            'clientes' => $clientes,
            'statuses' => self::STATUSES,
            'modelos'  => $modelos,
        ]);
    }

    /**
     * Duplica uma proposta: mesmo conteúdo e mesmos itens, cliente e status
     * voltam ao início.
     *
     * Nasce como "Cópia de X" para não se confundir com a original nas listas,
     * e o token público não é copiado - cada proposta precisa do seu, senão
     * dois documentos diferentes responderiam no mesmo link de assinatura.
     */
    public function duplicar(Proposta $proposta): RedirectResponse
    {
        $proposta->load('itens');

        $nova = $proposta->replicate(['token_publico', 'assinado_em', 'assinado_por_nome']);
        $nova->titulo = 'Cópia de ' . $proposta->titulo;
        $nova->status = 'rascunho';
        $nova->token_publico = null;
        $nova->assinado_em = null;
        $nova->assinado_por_nome = null;
        $nova->save();

        foreach ($proposta->itens as $item) {
            $novoItem = $item->replicate();
            $novoItem->proposta_id = $nova->id;
            $novoItem->save();
        }

        return redirect()->route('propostas.edit', $nova)
            ->with('success', 'Proposta duplicada. Ajuste o que precisar e salve.');
    }

    public function update(Request $request, Proposta $proposta): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id'    => 'required|integer|exists:clientes,id',
            'titulo'        => 'required|string|max:255',
            'conteudo'      => 'nullable|string',
            'status'        => 'nullable|in:rascunho,enviada,visualizada,aprovada,recusada,cancelada',
            'data_validade' => 'nullable|date',
            'itens'         => 'nullable|array',
            'itens.*.descricao'      => 'required_with:itens|string',
            'itens.*.quantidade'     => 'required_with:itens|numeric|min:0',
            'itens.*.valor_unitario' => 'required_with:itens|numeric|min:0',
        ]);

        $itens = $request->input('itens', []);
        $total = collect($itens)->sum(fn($i) => (float)($i['valor_unitario'] ?? 0) * (float)($i['quantidade'] ?? 1));
        $validated['valor_total'] = $total;

        $proposta->update($validated);
        $this->syncItens($proposta, $itens);

        return redirect()->route('propostas.index')
            ->with('success', 'Proposta atualizada com sucesso!');
    }

    /* ------------------------------------------------------------------ */
    /*  Delete                                                              */
    /* ------------------------------------------------------------------ */

    public function destroy(Proposta $proposta): RedirectResponse
    {
        $proposta->delete();

        return redirect()->route('propostas.index')
            ->with('success', 'Proposta excluída com sucesso!');
    }

    /* ------------------------------------------------------------------ */
    /*  Generate public link                                                */
    /* ------------------------------------------------------------------ */

    public function gerarLink(Proposta $proposta): RedirectResponse
    {
        if (! $proposta->token_publico) {
            $proposta->gerarTokenPublico();
        }

        $link = route('propostas.publica', $proposta->token_publico);

        return redirect()->route('propostas.index')
            ->with('link_gerado', $link)
            ->with('proposta_link_id', $proposta->id);
    }

    /* ------------------------------------------------------------------ */
    /*  Generate PDF                                                        */
    /* ------------------------------------------------------------------ */

    public function gerarPdf(Proposta $proposta)
    {
        $proposta->load('cliente', 'itens');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('propostas.pdf', [
            'proposta' => $proposta,
            'statuses' => self::STATUSES,
        ])->setPaper('a4', 'portrait');
        
        return $pdf->stream('Proposta_' . str_pad($proposta->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    private function syncItens(Proposta $proposta, array $itens): void
    {
        // Remove todos os itens anteriores e reinsere na ordem enviada
        $proposta->itens()->delete();

        foreach ($itens as $ordem => $item) {
            $descricao = trim($item['descricao'] ?? '');
            if ($descricao === '') continue;

            $qty   = max(0, (float)($item['quantidade'] ?? 1));
            $unit  = max(0, (float)($item['valor_unitario'] ?? 0));
            $total = round($qty * $unit, 2);

            PropostaItem::create([
                'proposta_id'    => $proposta->id,
                'descricao'      => $descricao,
                'quantidade'     => $qty,
                'valor_unitario' => $unit,
                'valor_total'    => $total,
                'ordem'          => $ordem,
            ]);
        }

        // Atualiza o valor_total da proposta com a soma real
        $novoTotal = $proposta->itens()->sum('valor_total');
        $proposta->update(['valor_total' => $novoTotal]);
    }
}
