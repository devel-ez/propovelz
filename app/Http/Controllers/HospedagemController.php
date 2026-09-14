<?php

namespace App\Http\Controllers;

use App\Support\Vencimentos;
use Illuminate\Http\Request;

class HospedagemController extends Controller
{
    public function index(Request $request)
    {
        // Por padrão esconde clientes inativos: o painel serve para cuidar do
        // que está em manutenção agora. O filtro existe para quando você
        // precisar conferir o histórico de quem saiu.
        $incluirInativos = $request->boolean('inativos');

        $itens  = Vencimentos::itens($incluirInativos);
        $resumo = Vencimentos::resumo($itens);

        return view('hospedagens.index', compact('itens', 'resumo', 'incluirInativos'));
    }
}
