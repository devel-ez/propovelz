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
        'ativo',
    ];

    /**
     * Valor inicial em memoria. O banco ja tem default(true), mas um projeto
     * recem-criado por create() nao recebe o valor de volta - sem isto, ele
     * fica com ativo=NULL ate ser recarregado, e aparece como inativo para
     * quem consultar o objeto na mesma requisicao.
     */
    protected $attributes = [
        'ativo' => true,
    ];

    /** Propriedade, nao metodo casts(): o metodo so vale do Laravel 10 em diante. */
    protected $casts = [
        'hospedagem_vigencia' => 'date',
        'dominio_vigencia' => 'date',
        'ativo' => 'boolean',
    ];

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

    /** Caderno do projeto: anotacoes mais recentes primeiro. */
    public function anotacoes(): HasMany
    {
        return $this->hasMany(ProjetoAnotacao::class)->latest();
    }
}
