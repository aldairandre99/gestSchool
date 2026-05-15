<?php

namespace Database\Seeders;

use App\Models\Curriculo;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtribuicoesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('A criar atribuições professor-turma-disciplina...');

        $professoresPorSigla = $this->mapearProfessoresPorSigla();
        $turmas = Turma::with('classe', 'curso')->get();

        $rows = [];
        $ts = now();

        foreach ($turmas as $turma) {
            $disciplinasIds = Curriculo::where('classe_id', $turma->classe_id)
                ->where(function ($q) use ($turma) {
                    if ($turma->curso_id) {
                        $q->where('curso_id', $turma->curso_id);
                    } else {
                        $q->whereNull('curso_id');
                    }
                })
                ->pluck('disciplina_id')
                ->all();

            foreach ($disciplinasIds as $discId) {
                $sigla = DB::table('disciplinas')->where('id', $discId)->value('sigla');
                $candidatos = $professoresPorSigla[$sigla] ?? [];

                if (empty($candidatos)) {
                    continue; // sem professor para esta disciplina (não devia acontecer)
                }

                $rows[] = [
                    'professor_id' => $candidatos[array_rand($candidatos)],
                    'turma_id' => $turma->id,
                    'disciplina_id' => $discId,
                    'ano_lectivo_id' => $turma->ano_lectivo_id,
                    'created_at' => $ts,
                    'updated_at' => $ts,
                ];

                if (count($rows) >= 500) {
                    DB::table('atribuicoes')->insertOrIgnore($rows);
                    $rows = [];
                }
            }
        }

        if (! empty($rows)) {
            DB::table('atribuicoes')->insertOrIgnore($rows);
        }

        $this->command?->info('Atribuições: '.DB::table('atribuicoes')->count());
    }

    /** @return array<string, list<int>> sigla → professor_ids */
    private function mapearProfessoresPorSigla(): array
    {
        $mapaEspecialidade = [
            'Língua Portuguesa' => ['POR'],
            'Matemática' => ['MAT', 'EST'],
            'Inglês' => ['ING'],
            'Francês' => ['FRA'],
            'Educação Física' => ['EDF'],
            'História' => ['HIS', 'EMC'],
            'Geografia' => ['GEO'],
            'Física' => ['FIS', 'MAT'],
            'Química' => ['QUI'],
            'Biologia' => ['BIO'],
            'Filosofia' => ['FIL', 'EMC'],
            'Sociologia' => ['SOC', 'EMC'],
            'Psicologia' => ['PSI', 'SOC'],
            'Economia' => ['ECO', 'GEST'],
            'Direito' => ['DIR', 'DT'],
            'Contabilidade' => ['CTB', 'AUD'],
            'Gestão' => ['GEST', 'RH'],
            'Recursos Humanos' => ['RH', 'PSI'],
            'Informática' => ['TIC', 'PROG', 'SO'],
            'Engenharia de Software' => ['PROG', 'BD'],
            'Redes e Sistemas' => ['RED', 'SO'],
            'Cozinha e Pastelaria' => ['COZ', 'HSA'],
            'Hotelaria' => ['THT', 'RB'],
            'Ensino Primário' => ['EM', 'POR', 'MAT'],
        ];

        $mapaSigla = [];
        $professores = Professor::all(['id', 'especialidade']);
        foreach ($professores as $p) {
            $siglas = $mapaEspecialidade[$p->especialidade] ?? [];
            foreach ($siglas as $s) {
                $mapaSigla[$s][] = $p->id;
            }
        }

        return $mapaSigla;
    }
}
