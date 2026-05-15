<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['nome', 'nivel', 'ordem'];

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }

    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'curso_classe')
            ->withPivot('ano')
            ->withTimestamps();
    }

    public function curriculo(): HasMany
    {
        return $this->hasMany(Curriculo::class);
    }

    public function disciplinas(): BelongsToMany
    {
        return $this->belongsToMany(Disciplina::class, 'curriculo')
            ->wherePivotNull('curso_id')
            ->withPivot('curso_id')
            ->withTimestamps();
    }

    public function disciplinasParaCurso(?int $cursoId)
    {
        if (! $cursoId) {
            return $this->disciplinas();
        }
        return $this->belongsToMany(Disciplina::class, 'curriculo')
            ->wherePivot('curso_id', $cursoId)
            ->withPivot('curso_id')
            ->withTimestamps();
    }

    public function isEnsinoMedio(): bool
    {
        return $this->nivel === 'ensino_medio';
    }
}
