<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PropostaController;
use App\Http\Controllers\PropostaPublicaController;
use Illuminate\Support\Facades\Route;

// Home → propostas
Route::get('/', fn() => redirect()->route('propostas.index'));

// Propostas (CRUD interno)
Route::resource('propostas', PropostaController::class);

// Clientes (CRUD)
Route::resource('clientes', ClienteController::class);

// Gerar link público de uma proposta
Route::post('propostas/{proposta}/gerar-link', [PropostaController::class, 'gerarLink'])
    ->name('propostas.gerar-link');

// Rotas públicas (sem autenticação) — acesso do cliente
Route::get('/p/{token}', [PropostaPublicaController::class, 'show'])
    ->name('propostas.publica');

Route::post('/p/{token}/assinar', [PropostaPublicaController::class, 'assinar'])
    ->name('propostas.assinar');
