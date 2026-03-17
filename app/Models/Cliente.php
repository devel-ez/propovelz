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
    ];

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
}
