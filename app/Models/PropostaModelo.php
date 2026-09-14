<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Texto reutilizável para propostas.
 *
 * Guarda só o conteúdo: cliente, valores e itens são preenchidos na proposta.
 */
class PropostaModelo extends Model
{
    use SoftDeletes;

    protected $table = 'proposta_modelos';

    protected $fillable = [
        'tenant_id',
        'titulo',
        'conteudo',
    ];
}
