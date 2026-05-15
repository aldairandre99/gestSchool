<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Atribuicao;
use App\Models\Classe;
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
        $ano = AnoLectivo::firstOrCreate(
            ['codigo' => '2026/2027'],
            ['inicio' => '2026-09-15', 'fim' => '2027-07-31', 'activo' => true]
        );

        $trimestres = [
            ['numero' => 1, 'inicio' => '2026-09-15', 'fim' => '2026-12-15'],
            ['numero' => 2, 'inicio' => '2027-01-10', 'fim' => '2027-04-10'],
            ['numero' => 3, 'inicio' => '2027-04-15', 'fim' => '2027-07-15'],
        ];
        foreach ($trimestres as $t) {
            Trimestre::firstOrCreate(
                ['ano_lectivo_id' => $ano->id, 'numero' => $t['numero']],
                ['inicio' => $t['inicio'], 'fim' => $t['fim'], 'aberto' => $t['numero'] === 1]
            );
        }

        $classes = [
            ['nome' => '10ª', 'ordem' => 10, 'nivel' => 'Ensino Médio'],
            ['nome' => '11ª', 'ordem' => 11, 'nivel' => 'Ensino Médio'],
            ['nome' => '12ª', 'ordem' => 12, 'nivel' => 'Ensino Médio'],
        ];
        $classeModels = [];
        foreach ($classes as $c) {
            $classeModels[$c['nome']] = Classe::firstOrCreate(['nome' => $c['nome']], $c);
        }

        $disciplinas = [
            ['nome' => 'Matemática', 'sigla' => 'MAT', 'carga_horaria_semanal' => 5],
            ['nome' => 'Português', 'sigla' => 'POR', 'carga_horaria_semanal' => 5],
            ['nome' => 'Física', 'sigla' => 'FIS', 'carga_horaria_semanal' => 4],
            ['nome' => 'Química', 'sigla' => 'QUI', 'carga_horaria_semanal' => 4],
            ['nome' => 'Biologia', 'sigla' => 'BIO', 'carga_horaria_semanal' => 3],
            ['nome' => 'História', 'sigla' => 'HIS', 'carga_horaria_semanal' => 3],
            ['nome' => 'Geografia', 'sigla' => 'GEO', 'carga_horaria_semanal' => 3],
            ['nome' => 'Inglês', 'sigla' => 'ING', 'carga_horaria_semanal' => 3],
            ['nome' => 'Filosofia', 'sigla' => 'FIL', 'carga_horaria_semanal' => 2],
            ['nome' => 'Educação Física', 'sigla' => 'EDF', 'carga_horaria_semanal' => 2],
        ];
        $discModels = [];
        foreach ($disciplinas as $d) {
            $discModels[$d['sigla']] = Disciplina::firstOrCreate(['nome' => $d['nome']], $d);
        }

        foreach ($classeModels as $classe) {
            $classe->disciplinas()->syncWithoutDetaching(collect($discModels)->pluck('id'));
        }

        $turmas = [];
        foreach (['10ª', '11ª', '12ª'] as $nome) {
            $turmas[$nome] = Turma::firstOrCreate(
                ['classe_id' => $classeModels[$nome]->id, 'ano_lectivo_id' => $ano->id, 'nome' => 'A'],
                ['sala' => 'Sala 1', 'turno' => 'Manhã', 'capacidade' => 40]
            );
        }

        $alunoDemo = Aluno::where('numero_processo', 'AL-2026-0001')->first();
        if ($alunoDemo) {
            Matricula::firstOrCreate(
                ['aluno_id' => $alunoDemo->id, 'ano_lectivo_id' => $ano->id],
                [
                    'turma_id' => $turmas['10ª']->id,
                    'numero_matricula' => 'M-2026-0001',
                    'data_matricula' => $ano->inicio,
                    'estado' => 'activa',
                ]
            );
        }

        $professorDemo = Professor::where('numero_professor', 'PROF-0001')->first();
        if ($professorDemo) {
            Atribuicao::firstOrCreate([
                'professor_id' => $professorDemo->id,
                'turma_id' => $turmas['10ª']->id,
                'disciplina_id' => $discModels['MAT']->id,
                'ano_lectivo_id' => $ano->id,
            ]);
            Atribuicao::firstOrCreate([
                'professor_id' => $professorDemo->id,
                'turma_id' => $turmas['10ª']->id,
                'disciplina_id' => $discModels['FIS']->id,
                'ano_lectivo_id' => $ano->id,
            ]);
        }
        $assistenteDemo = Professor::where('numero_professor', 'PROF-0002')->first();
        if ($assistenteDemo) {
            Atribuicao::firstOrCreate([
                'professor_id' => $assistenteDemo->id,
                'turma_id' => $turmas['10ª']->id,
                'disciplina_id' => $discModels['POR']->id,
                'ano_lectivo_id' => $ano->id,
            ]);
        }
    }
}
