<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = [
        'atribuicao_id', 'data', 'numero', 'hora_inicio', 'hora_fim',
        'sumario', 'conteudo_planeado', 'registado_por',
    ];

    protected function casts(): array
    {
        return ['data' => 'date'];
    }

    public function atribuicao(): BelongsTo
    {
        return $this->belongsTo(Atribuicao::class);
    }

    public function presencas(): HasMany
    {
        return $this->hasMany(Presenca::class);
    }

    public function registadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registado_por');
    }
}
