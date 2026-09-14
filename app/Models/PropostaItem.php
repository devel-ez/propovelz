<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropostaItem extends Model
{
    protected $table = 'proposta_itens';

    protected $fillable = [
        'proposta_id',
        'descricao',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'ordem',
    ];

    /** Propriedade, nao metodo casts(): o metodo so vale do Laravel 10 em diante. */
    protected $casts = [
        'quantidade'     => 'decimal:2',
        'valor_unitario' => 'decimal:2',
        'valor_total'    => 'decimal:2',
    ];

    public function proposta(): BelongsTo
    {
        return $this->belongsTo(Proposta::class);
    }
}
