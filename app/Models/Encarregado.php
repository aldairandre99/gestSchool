<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Encarregado extends Model
{
    use HasFactory;

    protected $table = 'encarregados';

    protected $fillable = [
        'user_id',
        'bi',
        'data_nascimento',
        'sexo',
        'profissao',
        'local_trabalho',
        'morada',
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

    public function alunos(): BelongsToMany
    {
        return $this->belongsToMany(Aluno::class, 'aluno_encarregado')
            ->withPivot(['parentesco', 'principal'])
            ->withTimestamps();
    }
}
