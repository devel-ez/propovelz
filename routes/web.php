<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\HospedagemController;
use App\Http\Controllers\LixeiraController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProjetoAnotacaoController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\PropostaController;
use App\Http\Controllers\PropostaModeloController;
use App\Http\Controllers\PropostaPublicaController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\WhoisController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autenticação
|--------------------------------------------------------------------------
| Sem estas três rotas o painel fica aberto para qualquer visitante — e ele
| guarda as credenciais dos clientes (tabela cliente_credenciais).
| A rota GET precisa se chamar "login": o middleware Authenticate
| redireciona para route('login') quando alguém não está autenticado.
*/
Route::get('login',   [LoginController::class, 'show'])->name('login');
Route::post('login',  [LoginController::class, 'login'])->name('login.attempt');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Área restrita — tudo aqui exige estar logado
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Home → Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Propostas (CRUD interno)
    Route::resource('propostas', PropostaController::class);

    // Clientes (CRUD)
    Route::resource('clientes', ClienteController::class);
    // Inativar/reativar sem apagar: some dos vencimentos, mantem o histórico
    Route::patch('clientes/{cliente}/toggle-ativo', [ClienteController::class, 'toggleAtivo'])
        ->name('clientes.toggle-ativo');

    // Projetos (CRUD + Kanban)
    Route::resource('projetos', ProjetoController::class);
    Route::resource('projetos.tarefas', TarefaController::class)->except(['index', 'create', 'show', 'edit']);
    Route::post('projetos/{projeto}/tarefas/reorder', [TarefaController::class, 'reorder'])->name('projetos.tarefas.reorder');

    // Caderno do projeto (anotações com histórico)
    Route::post('projetos/{projeto}/anotacoes', [ProjetoAnotacaoController::class, 'store'])
        ->name('projetos.anotacoes.store');
    Route::delete('projetos/{projeto}/anotacoes/{anotacao}', [ProjetoAnotacaoController::class, 'destroy'])
        ->name('projetos.anotacoes.destroy');

    // Hospedagens e domínios (vencimentos)
    Route::get('hospedagens', [HospedagemController::class, 'index'])->name('hospedagens.index');

    // Atualizar todos vem antes das rotas com {projeto}: 'dominios' também
    // casaria com o curinga, e a primeira que casar é a que vale.
    Route::post('hospedagens/dominios/whois-todos', [HospedagemController::class, 'atualizarTodosDominios'])
        ->name('hospedagens.dominios.todos');

    // Vencimento digitado à mão: hospedagem não tem de onde buscar a data
    Route::patch('hospedagens/{projeto}/hospedagem', [HospedagemController::class, 'salvarHospedagem'])
        ->name('hospedagens.hospedagem');
    Route::patch('hospedagens/{projeto}/dominio', [HospedagemController::class, 'salvarDominio'])
        ->name('hospedagens.dominio');

    // Busca no whois a data de um domínio
    Route::post('hospedagens/{projeto}/dominio/whois', [HospedagemController::class, 'atualizarDominio'])
        ->name('hospedagens.dominio.whois');

    // Lixeira: recuperar o que foi excluído, ou apagar de vez
    Route::get('lixeira', [LixeiraController::class, 'index'])->name('lixeira.index');
    Route::patch('lixeira/{tipo}/{id}/restaurar', [LixeiraController::class, 'restaurar'])->name('lixeira.restaurar');
    Route::delete('lixeira/{tipo}/{id}', [LixeiraController::class, 'destruir'])->name('lixeira.destruir');
    Route::delete('lixeira', [LixeiraController::class, 'esvaziar'])->name('lixeira.esvaziar');

    // Consulta Whois
    Route::post('whois', [WhoisController::class, 'consultar'])->name('whois.consultar');

    // Faturas (CRUD + toggle pago + grupo + PDF)
    Route::resource('faturas', FaturaController::class)->except(['show']);
    Route::patch('faturas/{fatura}/toggle-pago', [FaturaController::class, 'togglePago'])->name('faturas.toggle-pago');
    Route::get('faturas/grupo/{grupo}/edit', [FaturaController::class, 'editGrupo'])->name('faturas.grupo.edit');
    Route::put('faturas/grupo/{grupo}', [FaturaController::class, 'updateGrupo'])->name('faturas.grupo.update');
    Route::delete('faturas/grupo/{grupo}', [FaturaController::class, 'destroyGrupo'])->name('faturas.grupo.destroy');
    Route::get('faturas/{fatura}/pdf', [FaturaController::class, 'pdf'])->name('faturas.pdf');

    // Gerar PDF da proposta
    Route::get('propostas/{proposta}/pdf', [PropostaController::class, 'gerarPdf'])
        ->name('propostas.pdf');

    // Gerar link público de uma proposta
    Route::post('propostas/{proposta}/gerar-link', [PropostaController::class, 'gerarLink'])
        ->name('propostas.gerar-link');

    // Duplicar proposta (mesmo conteúdo e itens, cliente a definir)
    Route::post('propostas/{proposta}/duplicar', [PropostaController::class, 'duplicar'])
        ->name('propostas.duplicar');

    // Modelos de proposta: textos que se repetem de cliente para cliente
    Route::get('modelos', [PropostaModeloController::class, 'index'])->name('modelos.index');
    Route::get('modelos/{modelo}/conteudo', [PropostaModeloController::class, 'conteudo'])->name('modelos.conteudo');
    Route::get('modelos/{modelo}/editar', [PropostaModeloController::class, 'edit'])->name('modelos.edit');
    Route::post('modelos', [PropostaModeloController::class, 'store'])->name('modelos.store');
    Route::put('modelos/{modelo}', [PropostaModeloController::class, 'update'])->name('modelos.update');
    Route::delete('modelos/{modelo}', [PropostaModeloController::class, 'destroy'])->name('modelos.destroy');

    // Perfil: nome, e-mail, foto e troca de senha.
    // A rota da foto vem antes porque 'perfil/avatar' também casaria com
    // qualquer rota de perfil com parâmetro, se um dia existir.
    Route::get('perfil/avatar', [PerfilController::class, 'avatar'])->name('perfil.avatar');
    Route::get('perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('perfil/senha', [PerfilController::class, 'senha'])->name('perfil.senha');
    Route::delete('perfil/avatar', [PerfilController::class, 'removerAvatar'])->name('perfil.avatar.remover');
});

/*
|--------------------------------------------------------------------------
| Rotas públicas — acesso do cliente, SEM autenticação
|--------------------------------------------------------------------------
| O cliente abre o link da proposta e assina. Se estas rotas entrarem no
| grupo acima, todo link de proposta já enviado deixa de funcionar.
*/
Route::get('/p/{token}', [PropostaPublicaController::class, 'show'])
    ->name('propostas.publica');

Route::post('/p/{token}/assinar', [PropostaPublicaController::class, 'assinar'])
    ->name('propostas.assinar');
