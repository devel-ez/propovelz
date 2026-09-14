<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proposta;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Lixeira: o que foi excluído, com opção de recuperar ou apagar de vez.
 *
 * Só Clientes e Propostas guardam exclusão reversível (usam SoftDeletes).
 * Projetos, tarefas e faturas são apagados direto - por isso não aparecem aqui.
 *
 * Atenção ao apagar de vez: as chaves estrangeiras fazem o estrago andar
 * sozinho. Um cliente levado embora leva projetos, propostas e credenciais
 * junto (as faturas ficam, sem vínculo). A tela avisa antes.
 */
class LixeiraController extends Controller
{
    /** Tipos suportados: chave na URL => [modelo, rótulo singular, rótulo plural] */
    private const TIPOS = [
        'cliente'  => [Cliente::class,  'Cliente',  'Clientes'],
        'proposta' => [Proposta::class, 'Proposta', 'Propostas'],
    ];

    public function index(): View
    {
        $excluidos = [];

        foreach (self::TIPOS as $chave => [$modelo, $singular, $plural]) {
            $registros = $modelo::onlyTrashed()->latest('deleted_at')->get();

            $excluidos[$chave] = [
                'singular' => $singular,
                'plural'   => $plural,
                'plural_min' => mb_strtolower($plural),
                'registros' => $registros->map(fn ($r) => [
                    'id'         => $r->id,
                    'titulo'     => $chave === 'cliente' ? $r->nome : $r->titulo,
                    'detalhe'    => $this->detalhe($chave, $r),
                    'excluido_em' => $r->deleted_at,
                ]),
            ];
        }

        $total = collect($excluidos)->sum(fn ($g) => $g['registros']->count());

        return view('lixeira.index', compact('excluidos', 'total'));
    }

    /**
     * O que mais seria destruído junto, para o aviso antes de apagar de vez.
     */
    private function detalhe(string $tipo, $registro): ?string
    {
        if ($tipo === 'cliente') {
            $projetos    = $registro->projetos()->count();
            $propostas   = $registro->propostas()->count();
            $credenciais = $registro->credenciais()->count();

            $partes = array_filter([
                $projetos    ? "{$projetos} " . ($projetos === 1 ? 'projeto' : 'projetos') : null,
                $propostas   ? "{$propostas} " . ($propostas === 1 ? 'proposta' : 'propostas') : null,
                $credenciais ? "{$credenciais} " . ($credenciais === 1 ? 'acesso' : 'acessos') : null,
            ]);

            return $partes ? 'leva junto: ' . implode(', ', $partes) : null;
        }

        $itens = $registro->itens()->count();

        return $itens ? "leva junto: {$itens} " . ($itens === 1 ? 'item' : 'itens') : null;
    }

    public function restaurar(string $tipo, int $id): RedirectResponse
    {
        $registro = $this->buscar($tipo, $id);

        $registro->restore();

        $nome = $tipo === 'cliente' ? $registro->nome : $registro->titulo;

        return back()->with('success', "\"{$nome}\" recuperado.");
    }

    public function destruir(string $tipo, int $id): RedirectResponse
    {
        $registro = $this->buscar($tipo, $id);
        $nome = $tipo === 'cliente' ? $registro->nome : $registro->titulo;

        $registro->forceDelete();

        return back()->with('success', "\"{$nome}\" apagado definitivamente.");
    }

    public function esvaziar(): RedirectResponse
    {
        $apagados = 0;

        foreach (self::TIPOS as $chave => $definicao) {
            $apagados += $definicao[0]::onlyTrashed()->forceDelete();
        }

        $mensagem = $apagados === 0
            ? 'A lixeira já estava vazia.'
            : "Lixeira esvaziada: {$apagados} " . ($apagados === 1 ? 'registro apagado' : 'registros apagados') . ' definitivamente.';

        return back()->with('success', $mensagem);
    }

    /**
     * Busca dentro da lixeira, respeitando o tipo da URL.
     *
     * Sem o onlyTrashed, um id de registro ativo passaria por aqui e o
     * "restaurar" não faria nada visível - ou pior, o forceDelete apagaria
     * um registro que o usuário nem excluiu.
     */
    private function buscar(string $tipo, int $id)
    {
        abort_unless(isset(self::TIPOS[$tipo]), 404);

        $registro = self::TIPOS[$tipo][0]::onlyTrashed()->find($id);

        abort_if($registro === null, 404);

        return $registro;
    }
}
