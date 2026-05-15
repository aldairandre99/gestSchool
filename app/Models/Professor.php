<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professor extends Model
{
    use HasFactory;

    protected $table = 'professores';

    protected $fillable = [
        'user_id',
        'numero_professor',
        'bi',
        'data_nascimento',
        'sexo',
        'habilitacoes',
        'especialidade',
        'disciplinas',
        'data_admissao',
        'assistente',
        'morada',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'data_admissao' => 'date',
            'assistente' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function atribuicoes(): HasMany
    {
        return $this->hasMany(Atribuicao::class);
    }
}
