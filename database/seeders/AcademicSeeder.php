<?php

namespace Database\Seeders;

use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Curriculo;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\Trimestre;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAnosLectivosTrimestres();
        $classes = $this->seedClasses();
        $disciplinas = $this->seedDisciplinas();
        $cursos = $this->seedCursos($classes);
        $this->seedCurriculo($classes, $disciplinas, $cursos);
    }

    private function seedAnosLectivosTrimestres(): void
    {
        // 5 anos lectivos: 2022/2023 → 2026/2027 (último activo)
        $anos = [
            ['codigo' => '2022/2023', 'inicio' => '2022-09-12', 'fim' => '2023-07-28', 'activo' => false],
            ['codigo' => '2023/2024', 'inicio' => '2023-09-11', 'fim' => '2024-07-26', 'activo' => false],
            ['codigo' => '2024/2025', 'inicio' => '2024-09-09', 'fim' => '2025-07-25', 'activo' => false],
            ['codigo' => '2025/2026', 'inicio' => '2025-09-15', 'fim' => '2026-07-31', 'activo' => false],
            ['codigo' => '2026/2027', 'inicio' => '2026-09-14', 'fim' => '2027-07-30', 'activo' => true],
        ];

        foreach ($anos as $a) {
            $ano = AnoLectivo::firstOrCreate(['codigo' => $a['codigo']], $a);

            [$inicioAno, $fimAno] = [$ano->inicio, $ano->fim];
            $base = (int) substr($a['codigo'], 0, 4);

            $trimestres = [
                ['numero' => 1, 'inicio' => $inicioAno, 'fim' => "{$base}-12-15"],
                ['numero' => 2, 'inicio' => ($base + 1).'-01-10', 'fim' => ($base + 1).'-04-10'],
                ['numero' => 3, 'inicio' => ($base + 1).'-04-22', 'fim' => $fimAno],
            ];

            foreach ($trimestres as $t) {
                Trimestre::firstOrCreate(
                    ['ano_lectivo_id' => $ano->id, 'numero' => $t['numero']],
                    [
                        'inicio' => $t['inicio'],
                        'fim' => $t['fim'],
                        'aberto' => $a['activo'] && $t['numero'] === 1,
                    ]
                );
            }
        }
    }

    /** @return array<string, Classe> */
    private function seedClasses(): array
    {
        $classes = [];
        $nomes = ['1ª', '2ª', '3ª', '4ª', '5ª', '6ª', '7ª', '8ª', '9ª', '10ª', '11ª', '12ª', '13ª'];
        foreach ($nomes as $i => $nome) {
            $ordem = $i + 1;
            $classes[$nome] = Classe::firstOrCreate(
                ['nome' => $nome],
                ['nivel' => $ordem >= 10 ? 'ensino_medio' : 'ensino_base', 'ordem' => $ordem]
            );
        }

        return $classes;
    }

    /** @return array<string, Disciplina> */
    private function seedDisciplinas(): array
    {
        $disciplinas = [
            // tronco comum
            ['nome' => 'Língua Portuguesa', 'sigla' => 'POR', 'h' => 5],
            ['nome' => 'Matemática', 'sigla' => 'MAT', 'h' => 5],
            ['nome' => 'Educação Moral e Cívica', 'sigla' => 'EMC', 'h' => 2],
            ['nome' => 'Educação Física', 'sigla' => 'EDF', 'h' => 2],
            ['nome' => 'Inglês', 'sigla' => 'ING', 'h' => 3],
            ['nome' => 'Francês', 'sigla' => 'FRA', 'h' => 3],
            ['nome' => 'História', 'sigla' => 'HIS', 'h' => 3],
            ['nome' => 'Geografia', 'sigla' => 'GEO', 'h' => 3],
            // ciências
            ['nome' => 'Física', 'sigla' => 'FIS', 'h' => 4],
            ['nome' => 'Química', 'sigla' => 'QUI', 'h' => 4],
            ['nome' => 'Biologia', 'sigla' => 'BIO', 'h' => 4],
            // humanas
            ['nome' => 'Filosofia', 'sigla' => 'FIL', 'h' => 2],
            ['nome' => 'Sociologia', 'sigla' => 'SOC', 'h' => 2],
            ['nome' => 'Psicologia', 'sigla' => 'PSI', 'h' => 3],
            // económicas / contabilidade
            ['nome' => 'Economia', 'sigla' => 'ECO', 'h' => 3],
            ['nome' => 'Direito', 'sigla' => 'DIR', 'h' => 3],
            ['nome' => 'Direito do Trabalho', 'sigla' => 'DT', 'h' => 3],
            ['nome' => 'Contabilidade Geral', 'sigla' => 'CTB', 'h' => 5],
            ['nome' => 'Auditoria', 'sigla' => 'AUD', 'h' => 3],
            ['nome' => 'Estatística', 'sigla' => 'EST', 'h' => 3],
            ['nome' => 'Gestão', 'sigla' => 'GEST', 'h' => 4],
            ['nome' => 'Recursos Humanos', 'sigla' => 'RH', 'h' => 4],
            // técnicas/informática
            ['nome' => 'Tecnologias de Informação', 'sigla' => 'TIC', 'h' => 3],
            ['nome' => 'Programação', 'sigla' => 'PROG', 'h' => 5],
            ['nome' => 'Sistemas Operativos', 'sigla' => 'SO', 'h' => 3],
            ['nome' => 'Bases de Dados', 'sigla' => 'BD', 'h' => 4],
            ['nome' => 'Redes de Computadores', 'sigla' => 'RED', 'h' => 4],
            // hotelaria
            ['nome' => 'Cozinha e Pastelaria', 'sigla' => 'COZ', 'h' => 6],
            ['nome' => 'Restaurante e Bar', 'sigla' => 'RB', 'h' => 4],
            ['nome' => 'Técnicas de Hotelaria', 'sigla' => 'THT', 'h' => 4],
            ['nome' => 'Higiene e Segurança Alimentar', 'sigla' => 'HSA', 'h' => 2],
            // ensino primário
            ['nome' => 'Estudo do Meio', 'sigla' => 'EM', 'h' => 3],
        ];

        $disc = [];
        foreach ($disciplinas as $d) {
            $disc[$d['sigla']] = Disciplina::firstOrCreate(
                ['nome' => $d['nome']],
                ['sigla' => $d['sigla'], 'carga_horaria_semanal' => $d['h']]
            );
        }

        return $disc;
    }

    /**
     * @param  array<string, Classe>  $classes
     * @return array<string, Curso>
     */
    private function seedCursos(array $classes): array
    {
        $cursosData = [
            ['nome' => 'Informática', 'sigla' => 'INF', 'descricao' => 'Curso técnico de informática (4 anos): programação, sistemas, bases de dados e redes.'],
            ['nome' => 'Gestão de Recursos Humanos', 'sigla' => 'GRH', 'descricao' => 'Saídas para gestão de pessoas, recrutamento, formação e direito do trabalho.'],
            ['nome' => 'Hotelaria', 'sigla' => 'HOT', 'descricao' => 'Formação em cozinha, restaurante, bar e gestão hoteleira.'],
            ['nome' => 'Contabilidade e Gestão', 'sigla' => 'CG', 'descricao' => 'Saídas para contabilidade, auditoria e gestão de empresas.'],
            ['nome' => 'Físicas e Biológicas', 'sigla' => 'FB', 'descricao' => 'Saídas para ciências da saúde, engenharias e biotecnologia.'],
        ];

        $cursos = [];
        foreach ($cursosData as $c) {
            $cursos[$c['sigla']] = Curso::firstOrCreate(['sigla' => $c['sigla']], $c);
        }

        // INF: 4 anos (10ª-13ª). Restantes: 3 anos (10ª-12ª)
        foreach (['GRH', 'HOT', 'CG', 'FB'] as $sigla) {
            $cursos[$sigla]->classes()->syncWithoutDetaching([
                $classes['10ª']->id => ['ano' => 1],
                $classes['11ª']->id => ['ano' => 2],
                $classes['12ª']->id => ['ano' => 3],
            ]);
        }
        $cursos['INF']->classes()->syncWithoutDetaching([
            $classes['10ª']->id => ['ano' => 1],
            $classes['11ª']->id => ['ano' => 2],
            $classes['12ª']->id => ['ano' => 3],
            $classes['13ª']->id => ['ano' => 4],
        ]);

        return $cursos;
    }

    /**
     * @param  array<string, Classe>  $classes
     * @param  array<string, Disciplina>  $disc
     * @param  array<string, Curso>  $cursos
     */
    private function seedCurriculo(array $classes, array $disc, array $cursos): void
    {
        // ENSINO BASE (1ª-9ª) — sem curso
        $base = [
            '1ª' => ['POR', 'MAT', 'EM', 'EDF'],
            '2ª' => ['POR', 'MAT', 'EM', 'EDF'],
            '3ª' => ['POR', 'MAT', 'EM', 'EDF'],
            '4ª' => ['POR', 'MAT', 'EM', 'EDF', 'EMC'],
            '5ª' => ['POR', 'MAT', 'EM', 'EDF', 'EMC', 'HIS'],
            '6ª' => ['POR', 'MAT', 'EM', 'EDF', 'EMC', 'HIS', 'GEO'],
            '7ª' => ['POR', 'MAT', 'EMC', 'EDF', 'ING', 'HIS', 'GEO', 'BIO'],
            '8ª' => ['POR', 'MAT', 'EMC', 'EDF', 'ING', 'HIS', 'GEO', 'BIO', 'FIS', 'QUI'],
            '9ª' => ['POR', 'MAT', 'EMC', 'EDF', 'ING', 'HIS', 'GEO', 'BIO', 'FIS', 'QUI'],
        ];
        foreach ($base as $classeNome => $siglas) {
            foreach ($siglas as $s) {
                Curriculo::firstOrCreate([
                    'classe_id' => $classes[$classeNome]->id,
                    'curso_id' => null,
                    'disciplina_id' => $disc[$s]->id,
                ]);
            }
        }

        // ENSINO MÉDIO — disciplinas por curso
        $medio = [
            'INF' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIL', 'TIC', 'PROG', 'SO'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIL', 'PROG', 'SO', 'BD'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'PROG', 'BD', 'RED', 'GEST'],
                '13ª' => ['POR', 'PROG', 'BD', 'RED', 'GEST', 'EST'],
            ],
            'GRH' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIL', 'PSI', 'GEST', 'DIR'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'PSI', 'GEST', 'RH', 'DT'],
                '12ª' => ['POR', 'ING', 'EDF', 'GEST', 'RH', 'DT', 'EST', 'SOC'],
            ],
            'HOT' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIL', 'COZ', 'THT', 'HSA'],
                '11ª' => ['POR', 'ING', 'FRA', 'EDF', 'COZ', 'RB', 'THT', 'HSA'],
                '12ª' => ['POR', 'ING', 'FRA', 'EDF', 'COZ', 'RB', 'THT', 'GEST'],
            ],
            'CG' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIL', 'ECO', 'CTB', 'DIR'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'ECO', 'CTB', 'DIR', 'EST'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'CTB', 'AUD', 'GEST', 'EST'],
            ],
            'FB' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIL', 'FIS', 'QUI', 'BIO'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIS', 'QUI', 'BIO', 'EST'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIS', 'QUI', 'BIO', 'GEO'],
            ],
        ];

        foreach ($medio as $cursoSigla => $porClasse) {
            foreach ($porClasse as $classeNome => $siglas) {
                foreach ($siglas as $s) {
                    Curriculo::firstOrCreate([
                        'classe_id' => $classes[$classeNome]->id,
                        'curso_id' => $cursos[$cursoSigla]->id,
                        'disciplina_id' => $disc[$s]->id,
                    ]);
                }
            }
        }
    }
}
