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
        'classe_id', 'curso_id', 'ano_lectivo_id', 'nome', 'sala', 'turno', 'capacidade', 'director_turma_id',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
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
        $base = ($this->classe?->nome ?? '') . ' ' . $this->nome;
        if ($this->curso) $base .= ' · ' . $this->curso->sigla;
        return $base;
    }

    /** Versão para selects/options — sem badges, com ano lectivo entre parêntesis. */
    public function getDisplayLabelAttribute(): string
    {
        $base = $this->nome_completo;
        if (! $this->curso && ($this->classe?->nivel === 'ensino_base')) {
            $base .= ' · ' . __('base');
        }
        if ($this->anoLectivo) {
            $base .= ' (' . $this->anoLectivo->codigo . ')';
        }
        return $base;
    }
}
