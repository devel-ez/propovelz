<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Fatura;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FaturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Fatura::with(['cliente', 'projeto'])->orderBy('vencimento', 'desc');

        if ($request->filled('status')) {
            $query->where('pago', $request->status === 'pago');
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        $faturas = $query->get();
        $clientes = Cliente::orderBy('nome')->get();

        return view('faturas.index', compact('faturas', 'clientes'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $projetos = Projeto::orderBy('nome')->get();
        return view('faturas.create', compact('clientes', 'projetos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id'       => 'nullable|exists:clientes,id',
            'projeto_id'       => 'nullable|exists:projetos,id',
            'tipo'             => 'required|in:unica,mensal',
            'descricao_servico'=> 'required|string|max:1000',
            'valor'            => 'nullable|numeric|min:0',
            'vencimento'       => 'nullable|date',
            'observacoes'      => 'nullable|string|max:2000',
            'mes_inicio'       => 'nullable|date', // primeiro mês para contrato mensal
            // Quantas parcelas gerar. Era 12 fixo; o contrato varia por cliente.
            'parcelas'         => 'nullable|integer|min:1|max:60',
            // Dia do vencimento. Limitado a 28 de propósito: dia 29, 30 ou 31
            // não existe em todo mês, e a fatura cairia em data errada.
            'dia_vencimento'   => 'nullable|integer|min:1|max:28',
        ]);

        if ($validated['tipo'] === 'mensal') {
            $parcelas = (int) ($validated['parcelas'] ?? 12);
            $dia      = (int) ($validated['dia_vencimento'] ?? 5);

            // Gera as faturas mensais agrupadas sob o mesmo contrato
            $grupoClone = (string) Str::uuid();
            $mesInicio  = Carbon::parse($validated['mes_inicio'] ?? now())->startOfMonth();

            for ($i = 0; $i < $parcelas; $i++) {
                $mes = $mesInicio->copy()->addMonths($i);
                Fatura::create([
                    'cliente_id'       => $validated['cliente_id'] ?? null,
                    'projeto_id'       => $validated['projeto_id'] ?? null,
                    'tipo'             => 'mensal',
                    'descricao_servico'=> $validated['descricao_servico'],
                    'valor'            => $validated['valor'] ?? null,
                    'mes_referencia'   => $mes->format('Y-m-d'),
                    'vencimento'       => $mes->copy()->day($dia)->format('Y-m-d'),
                    'pago'             => false,
                    'observacoes'      => $validated['observacoes'] ?? null,
                    'grupo_clone'      => $grupoClone,
                ]);
            }
        } else {
            Fatura::create([
                'cliente_id'       => $validated['cliente_id'] ?? null,
                'projeto_id'       => $validated['projeto_id'] ?? null,
                'tipo'             => 'unica',
                'descricao_servico'=> $validated['descricao_servico'],
                'valor'            => $validated['valor'] ?? null,
                'vencimento'       => $validated['vencimento'] ?? null,
                'pago'             => false,
                'observacoes'      => $validated['observacoes'] ?? null,
            ]);
        }

        // O texto trazia '12' fixo, que deixou de ser verdade quando o numero
        // de parcelas virou escolha do usuario.
        $mensagem = $validated['tipo'] === 'mensal'
            ? $parcelas . ($parcelas === 1 ? ' fatura mensal criada com sucesso!' : ' faturas mensais criadas com sucesso!')
            : 'Fatura criada com sucesso!';

        return redirect()->route('faturas.index')->with('success', $mensagem);
    }

    public function edit(Fatura $fatura)
    {
        $clientes = Cliente::orderBy('nome')->get();
        $projetos = Projeto::orderBy('nome')->get();
        return view('faturas.edit', compact('fatura', 'clientes', 'projetos'));
    }

    public function update(Request $request, Fatura $fatura)
    {
        $validated = $request->validate([
            'cliente_id'       => 'nullable|exists:clientes,id',
            'projeto_id'       => 'nullable|exists:projetos,id',
            'descricao_servico'=> 'required|string|max:1000',
            'valor'            => 'nullable|numeric|min:0',
            'vencimento'       => 'nullable|date',
            'observacoes'      => 'nullable|string|max:2000',
        ]);

        $fatura->update($validated);

        return redirect()->route('faturas.index')->with('success', 'Fatura atualizada com sucesso!');
    }

    public function destroy(Fatura $fatura)
    {
        $fatura->delete();
        return back()->with('success', 'Fatura excluída.');
    }

    /**
     * Exclui todas as faturas de um contrato mensal (grupo_clone)
     */
    public function destroyGrupo(string $grupo)
    {
        Fatura::where('grupo_clone', $grupo)->delete();
        return back()->with('success', 'Contrato mensal excluído por completo.');
    }

    /**
     * Formulário de edição do contrato inteiro (todas as parcelas)
     */
    public function editGrupo(string $grupo)
    {
        $faturas = Fatura::with(['cliente', 'projeto'])
            ->where('grupo_clone', $grupo)
            ->orderBy('mes_referencia')
            ->get();

        abort_if($faturas->isEmpty(), 404);

        $clientes = Cliente::orderBy('nome')->get();
        $projetos = Projeto::orderBy('nome')->get();
        $primeira = $faturas->first();

        return view('faturas.edit-grupo', compact('faturas', 'primeira', 'clientes', 'projetos', 'grupo'));
    }

    /**
     * Salva alterações do contrato inteiro
     */
    public function updateGrupo(Request $request, string $grupo)
    {
        $validated = $request->validate([
            'cliente_id'        => 'nullable|exists:clientes,id',
            'projeto_id'        => 'nullable|exists:projetos,id',
            'descricao_servico' => 'required|string|max:1000',
            'valor'             => 'nullable|numeric|min:0',
            'observacoes'       => 'nullable|string|max:2000',
            'dia_vencimento'    => 'nullable|integer|min:1|max:28',
        ]);

        $faturas = Fatura::where('grupo_clone', $grupo)->orderBy('mes_referencia')->get();
        $dia = $validated['dia_vencimento'] ?? 5;

        foreach ($faturas as $fatura) {
            $mesCarbon = Carbon::parse($fatura->mes_referencia)->startOfMonth();
            $fatura->update([
                'cliente_id'        => $validated['cliente_id'],
                'projeto_id'        => $validated['projeto_id'],
                'descricao_servico' => $validated['descricao_servico'],
                'valor'             => $validated['valor'],
                'observacoes'       => $validated['observacoes'],
                'vencimento'        => $mesCarbon->copy()->setDay(min($dia, $mesCarbon->daysInMonth))->format('Y-m-d'),
            ]);
        }

        return redirect()->route('faturas.index')->with('success', 'Contrato mensal atualizado com sucesso!');
    }

    /**
     * Toggle pago/pendente via PATCH (chamado via fetch/AJAX)
     */
    public function togglePago(Request $request, Fatura $fatura)
    {
        $pago = ! $fatura->pago;
        $dataPagamento = $pago ? ($request->data_pagamento ?? now()->toDateString()) : null;

        $fatura->update([
            'pago'           => $pago,
            'data_pagamento' => $dataPagamento,
        ]);

        return response()->json([
            'pago'           => (bool) $fatura->pago,
            'data_pagamento' => $dataPagamento
                ? \Carbon\Carbon::parse($dataPagamento)->format('d/m/Y')
                : null,
        ]);
    }

    /**
     * Gera PDF de uma fatura
     */
    public function pdf(Fatura $fatura)
    {
        $fatura->load(['cliente', 'projeto']);
        $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
        $pdf = Pdf::loadView('faturas.pdf', compact('fatura', 'meses'))
            ->setPaper('a4', 'portrait');
        $filename = 'fatura-' . $fatura->id . '.pdf';
        return $pdf->download($filename);
    }
}
