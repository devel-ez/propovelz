<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteCredencial extends Model
{
    protected $table = 'cliente_credenciais';

    protected $fillable = [
        'cliente_id',
        'sistema',
        'login',
        'senha',
        'observacao',
    ];

    protected $casts = [
        'senha' => 'encrypted',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
