<?php

namespace App\Http\Controllers;

use App\Models\Proposta;
use App\Models\PropostaModelo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Modelos de proposta: textos que se repetem de cliente para cliente.
 *
 * O contrato de manutenção é o caso típico. Em vez de reescrever as cláusulas
 * a cada proposta, o texto fica guardado aqui e é inserido no editor.
 *
 * A criação de modelo se dá a partir de uma proposta existente: você ajusta o
 * texto no editor (que já tem o Quill), e salva como modelo. Assim não há um
 * segundo editor para manter, e o modelo nasce exatamente do que você escreveu.
 */
class PropostaModeloController extends Controller
{
    public function index(): View
    {
        $modelos = PropostaModelo::orderBy('titulo')->get();

        return view('modelos.index', compact('modelos'));
    }

    /**
     * Conteúdo de um modelo, para o botão "Inserir modelo" do editor.
     *
     * Vem por requisição em vez de embutido na página: com vários modelos,
     * embutir todos deixaria pesada a abertura de qualquer proposta.
     */
    public function conteudo(PropostaModelo $modelo): JsonResponse
    {
        return response()->json([
            'titulo'   => $modelo->titulo,
            'conteudo' => $modelo->conteudo,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'proposta_id' => 'required|integer|exists:propostas,id',
            'titulo'      => 'required|string|max:255',
        ], [], ['titulo' => 'nome do modelo']);

        $proposta = Proposta::findOrFail($data['proposta_id']);

        if (trim((string) $proposta->conteudo) === '') {
            return back()->withErrors([
                'titulo' => 'Esta proposta está sem conteúdo. Escreva o texto antes de salvar como modelo.',
            ]);
        }

        PropostaModelo::create([
            'tenant_id' => $proposta->tenant_id,
            'titulo'    => $data['titulo'],
            'conteudo'  => $proposta->conteudo,
        ]);

        return redirect()->route('modelos.index')
            ->with('success', "Modelo \"{$data['titulo']}\" salvo. Já está disponível para inserir em qualquer proposta.");
    }

    public function update(Request $request, PropostaModelo $modelo): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
        ], [], ['titulo' => 'nome do modelo']);

        $modelo->update(['titulo' => $data['titulo']]);

        return back()->with('success', 'Modelo renomeado.');
    }

    public function destroy(PropostaModelo $modelo): RedirectResponse
    {
        $titulo = $modelo->titulo;
        $modelo->delete();

        return back()->with('success', "Modelo \"{$titulo}\" removido.");
    }
}
