<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Projeto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'cliente_id',
        'hospedagem_tipo',
        'hospedagem_vigencia',
        'dominio',
        'dominio_vigencia',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'hospedagem_vigencia' => 'date',
            'dominio_vigencia' => 'date',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function propostas(): BelongsToMany
    {
        return $this->belongsToMany(Proposta::class, 'projeto_proposta');
    }

    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class)->orderBy('ordem');
    }
}
