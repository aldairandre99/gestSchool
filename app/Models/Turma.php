<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id', 'ano_lectivo_id', 'nome', 'sala', 'turno', 'capacidade', 'director_turma_id',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function anoLectivo(): BelongsTo
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    public function directorTurma(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'director_turma_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function atribuicoes(): HasMany
    {
        return $this->hasMany(Atribuicao::class);
    }

    public function getNomeCompletoAttribute(): string
    {
        return ($this->classe?->nome ?? '') . ' ' . $this->nome;
    }
}
