<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Proposta extends Model
{
    use HasFactory, SoftDeletes;

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

    protected function casts(): array
    {
        return [
            'valor_total'       => 'decimal:2',
            'dados_faturamento' => 'array',
            'data_validade'     => 'date',
            'assinado_em'       => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
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
