<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Uma anotacao do caderno do projeto.
 *
 * @property string $texto
 */
class ProjetoAnotacao extends Model
{
    protected $table = 'projeto_anotacoes';

    protected $fillable = [
        'projeto_id',
        'user_id',
        'texto',
    ];

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
