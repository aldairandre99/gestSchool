<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnoLectivo extends Model
{
    use HasFactory;

    protected $table = 'anos_lectivos';

    protected $fillable = ['codigo', 'inicio', 'fim', 'activo'];

    protected function casts(): array
    {
        return [
            'inicio' => 'date',
            'fim' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }

    public function trimestres(): HasMany
    {
        return $this->hasMany(Trimestre::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function atribuicoes(): HasMany
    {
        return $this->hasMany(Atribuicao::class);
    }

    public static function activo(): ?self
    {
        return static::where('activo', true)->first();
    }
}
