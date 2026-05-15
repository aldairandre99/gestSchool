<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Atribuicao;
use App\Models\Classe;
use App\Models\Curriculo;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\Matricula;
use App\Models\Professor;
use App\Models\Trimestre;
use App\Models\Turma;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Ano Lectivo + Trimestres =====
        $ano = AnoLectivo::firstOrCreate(
            ['codigo' => '2026/2027'],
            ['inicio' => '2026-09-15', 'fim' => '2027-07-31', 'activo' => true]
        );

        foreach ([
            ['numero' => 1, 'inicio' => '2026-09-15', 'fim' => '2026-12-15'],
            ['numero' => 2, 'inicio' => '2027-01-10', 'fim' => '2027-04-10'],
            ['numero' => 3, 'inicio' => '2027-04-15', 'fim' => '2027-07-15'],
        ] as $t) {
            Trimestre::firstOrCreate(
                ['ano_lectivo_id' => $ano->id, 'numero' => $t['numero']],
                ['inicio' => $t['inicio'], 'fim' => $t['fim'], 'aberto' => $t['numero'] === 1]
            );
        }

        // ===== Classes (1ª-9ª base, 10ª-13ª médio) =====
        $classeModels = [];
        $nomes = ['1ª', '2ª', '3ª', '4ª', '5ª', '6ª', '7ª', '8ª', '9ª', '10ª', '11ª', '12ª', '13ª'];
        foreach ($nomes as $i => $nome) {
            $ordem = $i + 1;
            $nivel = $ordem >= 10 ? 'ensino_medio' : 'ensino_base';
            $classeModels[$nome] = Classe::firstOrCreate(
                ['nome' => $nome],
                ['nivel' => $nivel, 'ordem' => $ordem]
            );
        }

        // ===== Disciplinas =====
        $disciplinas = [
            // tronco comum
            ['nome' => 'Língua Portuguesa', 'sigla' => 'POR', 'h' => 5],
            ['nome' => 'Matemática', 'sigla' => 'MAT', 'h' => 5],
            ['nome' => 'Educação Moral e Cívica', 'sigla' => 'EMC', 'h' => 2],
            ['nome' => 'Educação Física', 'sigla' => 'EDF', 'h' => 2],
            ['nome' => 'Inglês', 'sigla' => 'ING', 'h' => 3],
            ['nome' => 'História', 'sigla' => 'HIS', 'h' => 3],
            ['nome' => 'Geografia', 'sigla' => 'GEO', 'h' => 3],
            // ciências
            ['nome' => 'Física', 'sigla' => 'FIS', 'h' => 4],
            ['nome' => 'Química', 'sigla' => 'QUI', 'h' => 4],
            ['nome' => 'Biologia', 'sigla' => 'BIO', 'h' => 3],
            // humanas/filosofia
            ['nome' => 'Filosofia', 'sigla' => 'FIL', 'h' => 2],
            ['nome' => 'Sociologia', 'sigla' => 'SOC', 'h' => 2],
            // económicas
            ['nome' => 'Economia', 'sigla' => 'ECO', 'h' => 3],
            ['nome' => 'Direito', 'sigla' => 'DIR', 'h' => 3],
            ['nome' => 'Contabilidade', 'sigla' => 'CTB', 'h' => 4],
            // técnicas/informática
            ['nome' => 'Tecnologias de Informação', 'sigla' => 'TIC', 'h' => 3],
            ['nome' => 'Programação', 'sigla' => 'PROG', 'h' => 4],
            ['nome' => 'Sistemas Operativos', 'sigla' => 'SO', 'h' => 3],
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

        // ===== Cursos (ensino médio) =====
        $cursosData = [
            ['nome' => 'Ciências Físicas e Biológicas', 'sigla' => 'CFB', 'descricao' => 'Saídas profissionais para ciências da saúde e engenharias.'],
            ['nome' => 'Ciências Económicas e Jurídicas', 'sigla' => 'CEJ', 'descricao' => 'Saídas para direito, gestão, contabilidade.'],
            ['nome' => 'Ciências Humanas', 'sigla' => 'CH', 'descricao' => 'Saídas para humanidades, línguas, ciências sociais.'],
            ['nome' => 'Informática de Gestão', 'sigla' => 'IG', 'descricao' => 'Curso técnico de informática (4 anos).'],
        ];
        $cursos = [];
        foreach ($cursosData as $c) {
            $cursos[$c['sigla']] = Curso::firstOrCreate(['sigla' => $c['sigla']], $c);
        }

        // ===== Curso ↔ Classes (médio) =====
        // CFB, CEJ, CH: 3 anos (10ª-12ª)
        // IG (técnico): 4 anos (10ª-13ª)
        foreach (['CFB', 'CEJ', 'CH'] as $sigla) {
            $cursos[$sigla]->classes()->syncWithoutDetaching([
                $classeModels['10ª']->id => ['ano' => 1],
                $classeModels['11ª']->id => ['ano' => 2],
                $classeModels['12ª']->id => ['ano' => 3],
            ]);
        }
        $cursos['IG']->classes()->syncWithoutDetaching([
            $classeModels['10ª']->id => ['ano' => 1],
            $classeModels['11ª']->id => ['ano' => 2],
            $classeModels['12ª']->id => ['ano' => 3],
            $classeModels['13ª']->id => ['ano' => 4],
        ]);

        // ===== Currículo =====
        // ENSINO BASE (1ª-9ª) — sem curso (curso_id NULL)
        $baseDisciplinas = [
            '1ª'  => ['POR', 'MAT', 'EM', 'EDF'],
            '2ª'  => ['POR', 'MAT', 'EM', 'EDF'],
            '3ª'  => ['POR', 'MAT', 'EM', 'EDF'],
            '4ª'  => ['POR', 'MAT', 'EM', 'EDF', 'EMC'],
            '5ª'  => ['POR', 'MAT', 'EM', 'EDF', 'EMC', 'HIS'],
            '6ª'  => ['POR', 'MAT', 'EM', 'EDF', 'EMC', 'HIS', 'GEO'],
            '7ª'  => ['POR', 'MAT', 'EMC', 'EDF', 'ING', 'HIS', 'GEO', 'BIO'],
            '8ª'  => ['POR', 'MAT', 'EMC', 'EDF', 'ING', 'HIS', 'GEO', 'BIO', 'FIS', 'QUI'],
            '9ª'  => ['POR', 'MAT', 'EMC', 'EDF', 'ING', 'HIS', 'GEO', 'BIO', 'FIS', 'QUI'],
        ];
        foreach ($baseDisciplinas as $classeNome => $siglas) {
            foreach ($siglas as $s) {
                Curriculo::firstOrCreate([
                    'classe_id' => $classeModels[$classeNome]->id,
                    'curso_id' => null,
                    'disciplina_id' => $disc[$s]->id,
                ]);
            }
        }

        // ENSINO MÉDIO (10ª-12ª/13ª) — disciplinas por curso (repetidas em cada curso)
        $medioPorCurso = [
            'CFB' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIS', 'QUI', 'BIO', 'FIL'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIS', 'QUI', 'BIO', 'FIL'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'FIS', 'QUI', 'BIO', 'FIL'],
            ],
            'CEJ' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'ECO', 'DIR', 'CTB', 'FIL'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'ECO', 'DIR', 'CTB', 'FIL'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'ECO', 'DIR', 'CTB', 'FIL'],
            ],
            'CH' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'HIS', 'GEO', 'FIL', 'SOC'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'HIS', 'GEO', 'FIL', 'SOC'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'HIS', 'GEO', 'FIL', 'SOC'],
            ],
            'IG' => [
                '10ª' => ['POR', 'MAT', 'ING', 'EDF', 'TIC', 'PROG', 'FIL'],
                '11ª' => ['POR', 'MAT', 'ING', 'EDF', 'TIC', 'PROG', 'SO'],
                '12ª' => ['POR', 'MAT', 'ING', 'EDF', 'PROG', 'SO', 'CTB'],
                '13ª' => ['POR', 'PROG', 'SO', 'CTB', 'TIC'],
            ],
        ];
        foreach ($medioPorCurso as $cursoSigla => $porClasse) {
            foreach ($porClasse as $classeNome => $siglas) {
                foreach ($siglas as $s) {
                    Curriculo::firstOrCreate([
                        'classe_id' => $classeModels[$classeNome]->id,
                        'curso_id' => $cursos[$cursoSigla]->id,
                        'disciplina_id' => $disc[$s]->id,
                    ]);
                }
            }
        }

        // ===== Turmas demo =====
        $turmas = [];
        // 10ª A de CFB
        $turmas['10A-CFB'] = Turma::firstOrCreate(
            ['classe_id' => $classeModels['10ª']->id, 'ano_lectivo_id' => $ano->id, 'nome' => 'A'],
            ['curso_id' => $cursos['CFB']->id, 'sala' => 'Sala 1', 'turno' => 'Manhã', 'capacidade' => 40]
        );
        // 8ª A (ensino base)
        $turmas['8A'] = Turma::firstOrCreate(
            ['classe_id' => $classeModels['8ª']->id, 'ano_lectivo_id' => $ano->id, 'nome' => 'A'],
            ['curso_id' => null, 'sala' => 'Sala 5', 'turno' => 'Tarde', 'capacidade' => 40]
        );

        // ===== Matrícula demo =====
        $alunoDemo = Aluno::where('numero_processo', 'AL-2026-0001')->first();
        if ($alunoDemo) {
            Matricula::firstOrCreate(
                ['aluno_id' => $alunoDemo->id, 'ano_lectivo_id' => $ano->id],
                [
                    'turma_id' => $turmas['10A-CFB']->id,
                    'numero_matricula' => 'M-2026-0001',
                    'data_matricula' => $ano->inicio,
                    'estado' => 'activa',
                ]
            );
        }

        // ===== Atribuições demo =====
        $professorDemo = Professor::where('numero_professor', 'PROF-0001')->first();
        if ($professorDemo) {
            foreach (['MAT', 'FIS'] as $s) {
                Atribuicao::firstOrCreate([
                    'professor_id' => $professorDemo->id,
                    'turma_id' => $turmas['10A-CFB']->id,
                    'disciplina_id' => $disc[$s]->id,
                    'ano_lectivo_id' => $ano->id,
                ]);
            }
        }
        $assistenteDemo = Professor::where('numero_professor', 'PROF-0002')->first();
        if ($assistenteDemo) {
            Atribuicao::firstOrCreate([
                'professor_id' => $assistenteDemo->id,
                'turma_id' => $turmas['10A-CFB']->id,
                'disciplina_id' => $disc['POR']->id,
                'ano_lectivo_id' => $ano->id,
            ]);
            // Português na 8ª também
            Atribuicao::firstOrCreate([
                'professor_id' => $assistenteDemo->id,
                'turma_id' => $turmas['8A']->id,
                'disciplina_id' => $disc['POR']->id,
                'ano_lectivo_id' => $ano->id,
            ]);
        }
    }
}
