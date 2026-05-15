<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'atribuicao_id', 'dia_semana', 'tempo', 'sala', 'observacao',
    ];

    public function atribuicao(): BelongsTo
    {
        return $this->belongsTo(Atribuicao::class);
    }

    public function getHoraInicioAttribute(): ?string
    {
        return config("escola.tempos_lectivos.{$this->tempo}.0");
    }

    public function getHoraFimAttribute(): ?string
    {
        return config("escola.tempos_lectivos.{$this->tempo}.1");
    }

    public static function diasSemana(): array
    {
        return [
            1 => 'Segunda',
            2 => 'Terça',
            3 => 'Quarta',
            4 => 'Quinta',
            5 => 'Sexta',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
    }
}
