<?php

namespace App\Http\Controllers;

use App\Models\Proposta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PropostaPublicaController extends Controller
{
    public function show(string $token): View
    {
        $proposta = Proposta::with(['cliente', 'itens'])
            ->where('token_publico', $token)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Atualiza status para "visualizada" se ainda estiver em "enviada"
        if ($proposta->status === 'enviada') {
            $proposta->update(['status' => 'visualizada']);
        }

        return view('propostas.publica', compact('proposta'));
    }

    public function assinar(Request $request, string $token): RedirectResponse
    {
        $proposta = Proposta::where('token_publico', $token)
            ->whereNull('deleted_at')
            ->firstOrFail();

        // Não permite re-assinar
        if ($proposta->assinado_em) {
            return redirect()->route('propostas.publica', $token)
                ->with('aviso', 'Esta proposta já foi assinada.');
        }

        $request->validate([
            'nome_assinante' => 'required|string|max:255|min:3',
        ]);

        $proposta->update([
            'assinado_por_nome' => $request->nome_assinante,
            'assinado_em'       => now(),
            'status'            => 'aprovada',
        ]);

        return redirect()->route('propostas.publica', $token)
            ->with('sucesso_assinatura', 'Proposta assinada com sucesso! Obrigado, ' . $request->nome_assinante . '.');
    }
}
