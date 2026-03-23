<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'projeto_id',
        'tipo',
        'descricao_servico',
        'valor',
        'mes_referencia',
        'vencimento',
        'pago',
        'data_pagamento',
        'observacoes',
        'grupo_clone',
    ];

    protected function casts(): array
    {
        return [
            'pago'            => 'boolean',
            'mes_referencia'  => 'date',
            'vencimento'      => 'date',
            'data_pagamento'  => 'date',
            'valor'           => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }
}
