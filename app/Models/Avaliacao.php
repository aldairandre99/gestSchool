<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'atribuicao_id', 'trimestre_id', 'tipo', 'titulo', 'data', 'peso', 'max_nota',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'peso' => 'decimal:2',
            'max_nota' => 'decimal:2',
        ];
    }

    public function atribuicao(): BelongsTo
    {
        return $this->belongsTo(Atribuicao::class);
    }

    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }
}
