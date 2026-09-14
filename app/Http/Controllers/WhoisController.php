<?php

namespace App\Http\Controllers;

use App\Support\ConsultaWhois;
use Illuminate\Http\Request;

/**
 * Consulta avulsa, usada pelo formulário de projeto: o botão que preenche a
 * data de vencimento enquanto o projeto é cadastrado.
 *
 * A lógica de consulta mora em App\Support\ConsultaWhois porque a tela de
 * Hospedagens também precisa dela - para atualizar um domínio só e para
 * atualizar todos de uma vez.
 */
class WhoisController extends Controller
{
    public function consultar(Request $request)
    {
        $dominio = ConsultaWhois::limpar($request->input('domain'));

        if ($dominio === '') {
            return response()->json(['error' => 'Domínio não informado.'], 400);
        }

        $data = ConsultaWhois::expiracao($dominio);

        if (! $data) {
            return response()->json([
                'error' => 'Não foi possível obter a data de expiração deste domínio.',
            ], 404);
        }

        return response()->json([
            'domain'     => $dominio,
            'expires_at' => $data->format('Y-m-d'),
        ]);
    }
}
