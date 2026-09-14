<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\ProjetoAnotacao;
use Illuminate\Http\Request;

/**
 * Caderno do projeto: anotacoes com data e hora.
 *
 * Sem edicao de proposito - o valor esta no historico. Corrigir algo e
 * escrever uma anotacao nova, que fica registrada junto com a anterior.
 */
class ProjetoAnotacaoController extends Controller
{
    public function store(Request $request, Projeto $projeto)
    {
        $data = $request->validate([
            'texto' => 'required|string|max:5000',
        ], [], ['texto' => 'anotação']);

        $projeto->anotacoes()->create([
            'user_id' => auth()->id(),
            'texto'   => $data['texto'],
        ]);

        return back()->with('success', 'Anotação registrada.');
    }

    public function destroy(Projeto $projeto, ProjetoAnotacao $anotacao)
    {
        // A anotacao precisa pertencer a este projeto: sem isso, trocar o
        // numero na URL apagaria anotacao de outro projeto.
        abort_unless($anotacao->projeto_id === $projeto->id, 404);

        $anotacao->delete();

        return back()->with('success', 'Anotação removida.');
    }
}
