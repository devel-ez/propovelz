<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Proposta extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Os status possíveis de uma proposta, e como cada um aparece na tela.
     *
     * Chave é o que vai gravado no banco; valor é o rótulo.
     *
     * Fica aqui para existir UM lugar só. O dashboard já quebrou por isso: ele
     * contava propostas com os nomes em inglês ('approved', 'draft'), enquanto
     * o resto do painel grava em português ('aprovada', 'rascunho'). Nenhum
     * filtro casava, então o card mostrava sempre zero e o gráfico saía vazio -
     * sem erro nenhum na tela.
     */
    public const STATUS = [
        'rascunho'    => 'Rascunho',
        'enviada'     => 'Enviada',
        'visualizada' => 'Visualizada',
        'aprovada'    => 'Aprovada',
        'recusada'    => 'Recusada',
        'cancelada'   => 'Cancelada',
    ];

    /** Para usar em regra de validação: 'nullable|in:rascunho,enviada,...' */
    public static function statusValidos(): string
    {
        return implode(',', array_keys(self::STATUS));
    }

    public static function rotuloDoStatus(?string $status): string
    {
        return self::STATUS[$status] ?? ($status ?: '—');
    }

    protected $fillable = [
        'tenant_id',
        'cliente_id',
        'titulo',
        'conteudo',
        'status',
        'valor_total',
        'dados_faturamento',
        'data_validade',
        'token_publico',
        'assinado_em',
        'assinado_por_nome',
    ];

    /** Propriedade, nao metodo casts(): o metodo so vale do Laravel 10 em diante. */
    protected $casts = [
        'valor_total'       => 'decimal:2',
        'dados_faturamento' => 'array',
        'data_validade'     => 'date',
        'assinado_em'       => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function projetos(): BelongsToMany
    {
        return $this->belongsToMany(Projeto::class, 'projeto_proposta');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(PropostaItem::class)->orderBy('ordem');
    }

    /**
     * Gera um token único para a proposta e salva no banco.
     */
    public function gerarTokenPublico(): string
    {
        $token = Str::random(40);
        $this->update(['token_publico' => $token]);
        return $token;
    }
}
