<?php

namespace App\Http\Controllers;

use App\Support\Vencimentos;

class HospedagemController extends Controller
{
    public function index()
    {
        $itens  = Vencimentos::itens();
        $resumo = Vencimentos::resumo($itens);

        return view('hospedagens.index', compact('itens', 'resumo'));
    }
}
