<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'sigla', 'descricao', 'activo'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'curso_classe')
            ->withPivot('ano')
            ->withTimestamps()
            ->orderByPivot('ano');
    }

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class);
    }
}
