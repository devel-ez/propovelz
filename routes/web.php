<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropostaController;
use App\Http\Controllers\PropostaPublicaController;
use Illuminate\Support\Facades\Route;

// Home → Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Propostas (CRUD interno)
Route::resource('propostas', PropostaController::class);

// Clientes (CRUD)
Route::resource('clientes', ClienteController::class);

// Projetos (CRUD + Kanban)
Route::resource('projetos', \App\Http\Controllers\ProjetoController::class);
Route::resource('projetos.tarefas', \App\Http\Controllers\TarefaController::class)->except(['index', 'create', 'show', 'edit']);
Route::post('projetos/{projeto}/tarefas/reorder', [\App\Http\Controllers\TarefaController::class, 'reorder'])->name('projetos.tarefas.reorder');

// Consulta Whois
Route::post('whois', [\App\Http\Controllers\WhoisController::class, 'consultar'])->name('whois.consultar');

// Faturas (CRUD + toggle pago + grupo + PDF)
Route::resource('faturas', \App\Http\Controllers\FaturaController::class)->except(['show']);
Route::patch('faturas/{fatura}/toggle-pago', [\App\Http\Controllers\FaturaController::class, 'togglePago'])->name('faturas.toggle-pago');
Route::get('faturas/grupo/{grupo}/edit', [\App\Http\Controllers\FaturaController::class, 'editGrupo'])->name('faturas.grupo.edit');
Route::put('faturas/grupo/{grupo}', [\App\Http\Controllers\FaturaController::class, 'updateGrupo'])->name('faturas.grupo.update');
Route::delete('faturas/grupo/{grupo}', [\App\Http\Controllers\FaturaController::class, 'destroyGrupo'])->name('faturas.grupo.destroy');
Route::get('faturas/{fatura}/pdf', [\App\Http\Controllers\FaturaController::class, 'pdf'])->name('faturas.pdf');

// Gerar PDF da proposta
Route::get('propostas/{proposta}/pdf', [PropostaController::class, 'gerarPdf'])
    ->name('propostas.pdf');

// Gerar link público de uma proposta
Route::post('propostas/{proposta}/gerar-link', [PropostaController::class, 'gerarLink'])
    ->name('propostas.gerar-link');

// Rotas públicas (sem autenticação) — acesso do cliente
Route::get('/p/{token}', [PropostaPublicaController::class, 'show'])
    ->name('propostas.publica');

Route::post('/p/{token}/assinar', [PropostaPublicaController::class, 'assinar'])
    ->name('propostas.assinar');
