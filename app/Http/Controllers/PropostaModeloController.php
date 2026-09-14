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
 * Há dois caminhos para criar um modelo, e os dois terminam no mesmo lugar:
 *   - escrever direto aqui, na página de modelos (criar ou editar)
 *   - salvar o texto de uma proposta existente, pela página de edição dela
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
        // Dois modos: escrevendo aqui (titulo + conteudo), ou salvando o texto
        // de uma proposta existente (proposta_id + titulo).
        $dados = $request->validate([
            'titulo'      => 'required|string|max:255',
            'conteudo'    => 'nullable|string',
            'proposta_id' => 'nullable|integer|exists:propostas,id',
        ], [], ['titulo' => 'nome do modelo', 'conteudo' => 'texto do modelo']);

        $conteudo = $dados['conteudo'] ?? null;

        if (empty(trim((string) $conteudo)) && ! empty($dados['proposta_id'])) {
            $conteudo = Proposta::find($dados['proposta_id'])?->conteudo;
        }

        if (empty(trim((string) $conteudo))) {
            return back()
                ->withInput()
                ->withErrors(['conteudo' => 'Escreva o texto do modelo antes de salvar.']);
        }

        PropostaModelo::create([
            'tenant_id' => 1,
            'titulo'    => $dados['titulo'],
            'conteudo'  => $conteudo,
        ]);

        return redirect()->route('modelos.index')
            ->with('success', "Modelo \"{$dados['titulo']}\" salvo. Já está disponível no seletor Inserir modelo.");
    }

    public function edit(PropostaModelo $modelo): View
    {
        return view('modelos.edit', compact('modelo'));
    }

    public function update(Request $request, PropostaModelo $modelo): RedirectResponse
    {
        $dados = $request->validate([
            'titulo'   => 'required|string|max:255',
            'conteudo' => 'required|string',
        ], [], ['titulo' => 'nome do modelo', 'conteudo' => 'texto do modelo']);

        $modelo->update($dados);

        return redirect()->route('modelos.index')
            ->with('success', "Modelo \"{$modelo->titulo}\" atualizado.");
    }

    public function destroy(PropostaModelo $modelo): RedirectResponse
    {
        $titulo = $modelo->titulo;
        $modelo->delete();

        return back()->with('success', "Modelo \"{$titulo}\" removido. As propostas que já usaram o texto não são afetadas.");
    }
}
