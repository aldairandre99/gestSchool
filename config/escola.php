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

    // Dias da semana lectivos (1=segunda, 7=domingo — ISO)
    'dias_lectivos' => [1, 2, 3, 4, 5],

    // Tempos lectivos diários — chave = nº do tempo, valor = [inicio, fim]
    'tempos_lectivos' => [
        1 => ['07:30', '08:15'],
        2 => ['08:15', '09:00'],
        3 => ['09:15', '10:00'],
        4 => ['10:00', '10:45'],
        5 => ['11:00', '11:45'],
        6 => ['11:45', '12:30'],
        7 => ['13:30', '14:15'],
        8 => ['14:15', '15:00'],
    ],

    // Tipos de eventos do calendário escolar (cor hex para o calendário)
    'tipos_evento' => [
        'feriado'  => ['nome' => 'Feriado', 'cor' => '#fc5a5a'],
        'ferias'   => ['nome' => 'Férias', 'cor' => '#ffc542'],
        'exame'    => ['nome' => 'Exame', 'cor' => '#a461d8'],
        'prova'    => ['nome' => 'Prova', 'cor' => '#0062ff'],
        'reuniao'  => ['nome' => 'Reunião', 'cor' => '#44ce42'],
        'evento'   => ['nome' => 'Evento', 'cor' => '#f2a654'],
    ],
];
