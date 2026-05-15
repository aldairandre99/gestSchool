<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presenca extends Model
{
    use HasFactory;

    protected $fillable = [
        'atribuicao_id', 'matricula_id', 'data', 'estado', 'observacao', 'registado_por',
    ];

    protected function casts(): array
    {
        return ['data' => 'date'];
    }

    public function atribuicao(): BelongsTo
    {
        return $this->belongsTo(Atribuicao::class);
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function registadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registado_por');
    }
}
