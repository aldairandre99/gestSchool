<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'sigla', 'carga_horaria_semanal', 'activa'];

    protected function casts(): array
    {
        return ['activa' => 'boolean'];
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'classe_disciplina')->withTimestamps();
    }

    public function atribuicoes(): HasMany
    {
        return $this->hasMany(Atribuicao::class);
    }
}
