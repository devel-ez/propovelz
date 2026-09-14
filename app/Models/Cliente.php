<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'nome',
        'email',
        'telefone',
        'documento',
        'ativo',
    ];

    /**
     * Propriedade, nao metodo casts(): o metodo so vale do Laravel 10 em diante.
     * Sem isto, 'ativo' volta do banco como 0/1 em vez de booleano.
     */
    protected $casts = [
        'ativo' => 'boolean',
    ];

    /** Só quem ainda mantém o site: usado para os vencimentos. */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function propostas(): HasMany
    {
        return $this->hasMany(Proposta::class);
    }

    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class);
    }

    public function credenciais(): HasMany
    {
        return $this->hasMany(ClienteCredencial::class)->orderBy('sistema');
    }

    public function faturas(): HasMany
    {
        return $this->hasMany(Fatura::class)->latest('vencimento');
    }
}
