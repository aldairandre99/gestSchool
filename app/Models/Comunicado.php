<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comunicado extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'conteudo', 'autor_id', 'alcance',
        'classe_id', 'turma_id', 'publicado_em',
    ];

    protected function casts(): array
    {
        return ['publicado_em' => 'datetime'];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    public function scopePublicados(Builder $query): Builder
    {
        return $query->whereNotNull('publicado_em')->where('publicado_em', '<=', now());
    }

    public function scopeVisivelPara(Builder $query, User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->where('alcance', 'todos');
            if ($user->hasAnyRole(['professor', 'professor_assistente'])) {
                $q->orWhere('alcance', 'professores');
            }
            if ($user->hasRole('encarregado')) {
                $q->orWhere('alcance', 'encarregados');
                $alunoIds = $user->encarregado?->alunos()->pluck('alunos.id') ?? collect();
                if ($alunoIds->isNotEmpty()) {
                    $turmaIds = \App\Models\Matricula::whereIn('aluno_id', $alunoIds)->pluck('turma_id')->unique();
                    if ($turmaIds->isNotEmpty()) {
                        $q->orWhere(function ($c) use ($turmaIds) {
                            $c->where('alcance', 'turma')->whereIn('turma_id', $turmaIds);
                        });
                    }
                }
            }
        });
    }
}
