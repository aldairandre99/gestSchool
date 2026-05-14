<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
        'user_id',
        'numero_processo',
        'bi',
        'data_nascimento',
        'sexo',
        'classe',
        'turma',
        'ano_lectivo',
        'nacionalidade',
        'naturalidade',
        'morada',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function encarregados(): BelongsToMany
    {
        return $this->belongsToMany(Encarregado::class, 'aluno_encarregado')
            ->withPivot(['parentesco', 'principal'])
            ->withTimestamps();
    }
}
