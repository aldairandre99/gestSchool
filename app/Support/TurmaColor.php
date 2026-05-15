<?php

namespace App\Support;

class TurmaColor
{
    /** Palette de 10 cores estáveis com bom contraste em badges/células. */
    public const PALETTE = [
        ['bg' => '#fee2e2', 'fg' => '#991b1b', 'border' => '#fca5a5'], // red
        ['bg' => '#ffedd5', 'fg' => '#9a3412', 'border' => '#fdba74'], // orange
        ['bg' => '#fef3c7', 'fg' => '#92400e', 'border' => '#fcd34d'], // amber
        ['bg' => '#dcfce7', 'fg' => '#166534', 'border' => '#86efac'], // green
        ['bg' => '#cffafe', 'fg' => '#155e75', 'border' => '#67e8f9'], // cyan
        ['bg' => '#dbeafe', 'fg' => '#1e40af', 'border' => '#93c5fd'], // blue
        ['bg' => '#e0e7ff', 'fg' => '#3730a3', 'border' => '#a5b4fc'], // indigo
        ['bg' => '#ede9fe', 'fg' => '#5b21b6', 'border' => '#c4b5fd'], // violet
        ['bg' => '#fce7f3', 'fg' => '#9d174d', 'border' => '#f9a8d4'], // pink
        ['bg' => '#f1f5f9', 'fg' => '#334155', 'border' => '#cbd5e1'], // slate
    ];

    public static function for(int $turmaId): array
    {
        return self::PALETTE[$turmaId % count(self::PALETTE)];
    }
}
