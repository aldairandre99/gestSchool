<?php

/**
 * Regras académicas configuráveis.
 *
 * Estes valores são os defaults — podem ser sobrescritos por turma/ano
 * através de query params nas pautas.
 */
return [

    // Escala de avaliação (0-20 padrão angolano)
    'nota_max' => 20,
    'nota_min' => 0,
    'nota_minima_aprovacao' => 10,

    // Regras de situação final (com base no nº de disciplinas negativas)
    'max_negativas_recurso' => 2,    // até 2 → vai a recurso
    // 3+ → reprovado directo

    // Pesos default dos trimestres (média simples)
    // Para média ponderada típica usar [1, 1, 2]
    'pesos_trimestres' => [1, 1, 1],

    // Etiquetas de situação
    'situacoes' => [
        'aprovado'  => 'aprovado',
        'recurso'   => 'recurso',
        'reprovado' => 'reprovado',
    ],
];
