<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trimestre extends Model
{
    use HasFactory;

    protected $fillable = ['ano_lectivo_id', 'numero', 'inicio', 'fim', 'aberto'];

    protected function casts(): array
    {
        return [
            'inicio' => 'date',
            'fim' => 'date',
            'aberto' => 'boolean',
        ];
    }

    public function anoLectivo(): BelongsTo
    {
        return $this->belongsTo(AnoLectivo::class);
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }
}
