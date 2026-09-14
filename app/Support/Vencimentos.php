<?php

namespace App\Support;

use App\Models\Projeto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Vencimentos de hospedagem e dominio dos projetos.
 *
 * Existe para o painel de Hospedagens e o dashboard usarem exatamente a
 * mesma regra: se cada um calculasse por conta propria, uma tela poderia
 * dizer "vence em 5 dias" e a outra "vence em 6".
 *
 * Projeto e dominio entram como itens separados porque vencem em datas
 * diferentes - o dominio pode ser do cliente e a hospedagem sua.
 */
class Vencimentos
{
    /** A partir de quantos dias o item entra em alerta. */
    public const ALERTA_DIAS = 30;

    /**
     * Todos os itens de hospedagem e dominio, do mais urgente ao menos.
     *
     * @param  bool  $incluirInativos  Inclui projetos de clientes marcados como
     *                                 inativos (desistiram de manter o site).
     * @return Collection<int, array<string, mixed>>
     */
    public static function itens(bool $incluirInativos = false): Collection
    {
        $projetos = Projeto::query()
            ->with('cliente:id,nome,ativo')
            ->where(function ($q) {
                $q->whereNotNull('hospedagem_tipo')
                  ->orWhereNotNull('hospedagem_vigencia')
                  ->orWhereNotNull('dominio')
                  ->orWhereNotNull('dominio_vigencia');
            })
            // Cliente inativo sai dos vencimentos para nao poluir o dashboard.
            // Nada e apagado: o historico dele continua no sistema e basta
            // reativar para os vencimentos voltarem a contar.
            ->when(! $incluirInativos, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('cliente', fn ($c) => $c->where('ativo', true))
                        ->orWhereDoesntHave('cliente');
                });
            })
            ->get();

        $hoje = Carbon::today();
        $itens = [];

        foreach ($projetos as $p) {
            if ($p->hospedagem_tipo || $p->hospedagem_vigencia) {
                $itens[] = self::linha('Hospedagem', $p->hospedagem_tipo ?: 'Hospedagem', $p, $p->hospedagem_vigencia, $hoje);
            }

            if ($p->dominio || $p->dominio_vigencia) {
                $itens[] = self::linha('Domínio', $p->dominio ?: 'Domínio', $p, $p->dominio_vigencia, $hoje);
            }
        }

        // Sem data vai para o fim: nao sabemos quando vence, entao nao
        // pode competir em urgencia com quem tem data marcada.
        return collect($itens)->sortBy(fn ($i) => $i['dias'] ?? PHP_INT_MAX)->values();
    }

    /**
     * @return array<string, mixed>
     */
    private static function linha(string $tipo, string $descricao, Projeto $projeto, $vigencia, Carbon $hoje): array
    {
        $data = $vigencia ? Carbon::parse($vigencia) : null;

        // diffInDays com $absolute = false devolve o sinal: negativo ja venceu.
        $dias = $data ? (int) $hoje->diffInDays($data, false) : null;

        return [
            'tipo'      => $tipo,
            'descricao' => $descricao,
            'projeto'   => $projeto,
            'cliente'   => $projeto->cliente,
            'vigencia'  => $data,
            'dias'      => $dias,
            'situacao'  => self::situacao($dias),
            'inativo'   => $projeto->cliente ? ! $projeto->cliente->ativo : false,
        ];
    }

    /** vencido | alerta | ok | sem-data */
    private static function situacao(?int $dias): string
    {
        if ($dias === null) {
            return 'sem-data';
        }

        if ($dias < 0) {
            return 'vencido';
        }

        return $dias <= self::ALERTA_DIAS ? 'alerta' : 'ok';
    }

    /**
     * Contagem por situacao, para os cartoes do topo.
     *
     * @param  Collection<int, array<string, mixed>>  $itens
     * @return array<string, int>
     */
    public static function resumo(Collection $itens): array
    {
        return [
            'total'    => $itens->count(),
            'vencidos' => $itens->where('situacao', 'vencido')->count(),
            'alerta'   => $itens->where('situacao', 'alerta')->count(),
            'ok'       => $itens->where('situacao', 'ok')->count(),
            'sem_data' => $itens->where('situacao', 'sem-data')->count(),
        ];
    }

    /**
     * O que precisa de atencao agora: vencidos e vencendo no prazo de alerta.
     *
     * @param  Collection<int, array<string, mixed>>  $itens
     * @return Collection<int, array<string, mixed>>
     */
    public static function urgentes(Collection $itens): Collection
    {
        return $itens->filter(fn ($i) => in_array($i['situacao'], ['vencido', 'alerta'], true))->values();
    }
}
