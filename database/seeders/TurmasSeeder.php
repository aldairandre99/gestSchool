<?php

namespace Database\Seeders;

use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Curso;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Database\Seeder;

class TurmasSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_PT');
        $faker->seed(20260516);

        $classes = Classe::orderBy('ordem')->get()->keyBy('nome');
        $cursos = Curso::all()->keyBy('sigla');
        $anos = AnoLectivo::orderBy('codigo')->get();
        $professoresIds = Professor::pluck('id')->all();

        if (empty($professoresIds)) {
            $this->command?->warn('TurmasSeeder: sem professores — director_turma ficará nulo.');
        }

        // Quantas turmas por classe no ensino base
        $turmasPorClasseBase = [
            '1ª' => 4, '2ª' => 4, '3ª' => 4, '4ª' => 4, '5ª' => 4, '6ª' => 4,
            '7ª' => 3, '8ª' => 3, '9ª' => 3,
        ];

        // Ensino médio: turmas por (classe × curso)
        $turmasMedio = [
            'INF' => ['10ª' => 1, '11ª' => 1, '12ª' => 1, '13ª' => 1],
            'GRH' => ['10ª' => 2, '11ª' => 1, '12ª' => 1],
            'HOT' => ['10ª' => 2, '11ª' => 2, '12ª' => 1],
            'CG' => ['10ª' => 2, '11ª' => 2, '12ª' => 2],
            'FB' => ['10ª' => 2, '11ª' => 2, '12ª' => 2],
        ];

        $turnos = ['Manhã', 'Tarde'];
        $letras = ['A', 'B', 'C', 'D', 'E', 'F'];
        $salaCounter = 0;

        foreach ($anos as $ano) {
            // ENSINO BASE
            foreach ($turmasPorClasseBase as $classeNome => $qtd) {
                $classe = $classes[$classeNome];
                for ($i = 0; $i < $qtd; $i++) {
                    Turma::firstOrCreate(
                        [
                            'classe_id' => $classe->id,
                            'ano_lectivo_id' => $ano->id,
                            'nome' => $letras[$i],
                        ],
                        [
                            'curso_id' => null,
                            'sala' => 'Sala '.(($salaCounter++ % 50) + 1),
                            'turno' => $turnos[$i % 2],
                            'capacidade' => 40,
                            'director_turma_id' => $professoresIds
                                ? $professoresIds[array_rand($professoresIds)]
                                : null,
                        ]
                    );
                }
            }

            // ENSINO MÉDIO
            foreach ($turmasMedio as $cursoSigla => $porClasse) {
                $curso = $cursos[$cursoSigla] ?? null;
                if (! $curso) {
                    continue;
                }
                foreach ($porClasse as $classeNome => $qtd) {
                    $classe = $classes[$classeNome];
                    for ($i = 0; $i < $qtd; $i++) {
                        Turma::firstOrCreate(
                            [
                                'classe_id' => $classe->id,
                                'ano_lectivo_id' => $ano->id,
                                'nome' => $letras[$i].'-'.$cursoSigla,
                            ],
                            [
                                'curso_id' => $curso->id,
                                'sala' => 'Sala '.(($salaCounter++ % 50) + 1),
                                'turno' => $turnos[$i % 2],
                                'capacidade' => 40,
                                'director_turma_id' => $professoresIds
                                    ? $professoresIds[array_rand($professoresIds)]
                                    : null,
                            ]
                        );
                    }
                }
            }
        }
    }
}
