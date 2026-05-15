<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos_escolares';

    protected $fillable = [
        'ano_lectivo_id', 'titulo', 'descricao', 'tipo',
        'data_inicio', 'data_fim', 'hora_inicio', 'hora_fim',
        'dia_inteiro', 'cor', 'classe_id', 'turma_id', 'criado_por',
    ];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'dia_inteiro' => 'boolean',
        ];
    }

    public function anoLectivo(): BelongsTo
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function getCorEfectivaAttribute(): string
    {
        if ($this->cor) return $this->cor;
        return config("escola.tipos_evento.{$this->tipo}.cor", '#76838f');
    }

    public function getTipoNomeAttribute(): string
    {
        return config("escola.tipos_evento.{$this->tipo}.nome", ucfirst($this->tipo));
    }

    public function getDataFimEfectivaAttribute()
    {
        return $this->data_fim ?? $this->data_inicio;
    }

    public function scopeNoIntervalo(Builder $query, $inicio, $fim): Builder
    {
        return $query->where(function ($q) use ($inicio, $fim) {
            $q->whereBetween('data_inicio', [$inicio, $fim])
              ->orWhereBetween('data_fim', [$inicio, $fim])
              ->orWhere(function ($qq) use ($inicio, $fim) {
                  $qq->where('data_inicio', '<=', $inicio)
                     ->where('data_fim', '>=', $fim);
              });
        });
    }

    public function scopeVisivelPara(Builder $query, User $user): Builder
    {
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario'])) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where(function ($qq) {
                $qq->whereNull('turma_id')->whereNull('classe_id');
            });

            if ($user->hasAnyRole(['professor', 'professor_assistente']) && $user->professor) {
                $turmaIds = $user->professor->atribuicoes()->pluck('turma_id')->unique();
                if ($turmaIds->isNotEmpty()) {
                    $q->orWhereIn('turma_id', $turmaIds);
                    $classeIds = \App\Models\Turma::whereIn('id', $turmaIds)->pluck('classe_id')->unique();
                    if ($classeIds->isNotEmpty()) $q->orWhereIn('classe_id', $classeIds);
                }
            }

            if ($user->hasRole('encarregado') && $user->encarregado) {
                $alunoIds = $user->encarregado->alunos()->pluck('alunos.id');
                $turmaIds = \App\Models\Matricula::whereIn('aluno_id', $alunoIds)->pluck('turma_id')->unique();
                if ($turmaIds->isNotEmpty()) {
                    $q->orWhereIn('turma_id', $turmaIds);
                    $classeIds = \App\Models\Turma::whereIn('id', $turmaIds)->pluck('classe_id')->unique();
                    if ($classeIds->isNotEmpty()) $q->orWhereIn('classe_id', $classeIds);
                }
            }
        });
    }
}
