<?php

namespace Database\Seeders;

use App\Models\AnoLectivo;
use App\Models\Atribuicao;
use App\Models\Trimestre;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PautasNotasPresencasSeeder extends Seeder
{
    /** Para anos históricos: 1 avaliação contínua por trimestre. */
    private const HIST_AVALIACOES_POR_TRIMESTRE = 1;

    /** Para o ano activo: prova + teste + contínua por trimestre. */
    private const ACTUAL_TIPOS = ['prova', 'teste', 'avaliacao_continua'];

    /** Quantas aulas simular no trimestre 1 do ano activo (~14 semanas lectivas). */
    private const AULAS_POR_ATRIBUICAO_TRIM1 = 12;

    public function run(): void
    {
        $anoActivo = AnoLectivo::where('activo', true)->first();
        if (! $anoActivo) {
            $this->command?->warn('Sem ano lectivo activo — a saltar PautasNotasPresencasSeeder.');

            return;
        }

        $this->command?->info('A gerar avaliações + notas + aulas + presenças...');

        $this->seedHistorico($anoActivo->id);
        $this->seedAnoActivo($anoActivo);
    }

    private function seedHistorico(int $anoActivoId): void
    {
        $atribuicoes = Atribuicao::where('ano_lectivo_id', '!=', $anoActivoId)
            ->select('id', 'ano_lectivo_id', 'turma_id', 'disciplina_id')
            ->get();

        $trimestresPorAno = $this->trimestresPorAno();
        $matriculasPorTurma = $this->matriculasPorTurma(['activa', 'aprovado', 'reprovado', 'transferido', 'desistente']);

        $ts = now();
        $bufferAval = [];
        $bufferAvalKeys = []; // para conseguir mapear novamente

        // 1. Inserir avaliações em massa
        foreach ($atribuicoes as $atrib) {
            foreach ($trimestresPorAno[$atrib->ano_lectivo_id] ?? [] as $trim) {
                $bufferAval[] = [
                    'atribuicao_id' => $atrib->id,
                    'trimestre_id' => $trim->id,
                    'tipo' => 'avaliacao_continua',
                    'titulo' => 'Avaliação Final do '.$trim->numero.'º Trimestre',
                    'data' => Carbon::parse($trim->fim)->subDays(5)->format('Y-m-d'),
                    'peso' => 1.00,
                    'max_nota' => 20.00,
                    'created_at' => $ts,
                    'updated_at' => $ts,
                ];
            }
            if (count($bufferAval) >= 1000) {
                DB::table('avaliacoes')->insert($bufferAval);
                $bufferAval = [];
            }
        }
        if (! empty($bufferAval)) {
            DB::table('avaliacoes')->insert($bufferAval);
        }

        // 2. Para cada avaliação histórica, inserir notas
        $this->command?->info('  → Notas históricas...');

        $batchSize = 1000;
        $bufferNotas = [];
        $totalNotas = 0;

        $atribIds = $atribuicoes->pluck('id')->all();

        // Iterar avaliações em chunks
        DB::table('avaliacoes')
            ->whereIn('atribuicao_id', $atribIds)
            ->select('id', 'atribuicao_id')
            ->orderBy('id')
            ->chunk(500, function ($avals) use (
                $atribuicoes, $matriculasPorTurma,
                &$bufferNotas, &$totalNotas, $ts, $batchSize
            ) {
                $atribIndex = $atribuicoes->keyBy('id');

                foreach ($avals as $aval) {
                    $atrib = $atribIndex[$aval->atribuicao_id] ?? null;
                    if (! $atrib) {
                        continue;
                    }
                    $matriculas = $matriculasPorTurma[$atrib->turma_id] ?? [];
                    foreach ($matriculas as $matId) {
                        $bufferNotas[] = [
                            'avaliacao_id' => $aval->id,
                            'matricula_id' => $matId,
                            'valor' => $this->notaRealista(),
                            'observacao' => null,
                            'created_at' => $ts,
                            'updated_at' => $ts,
                        ];
                        $totalNotas++;

                        if (count($bufferNotas) >= $batchSize) {
                            DB::table('notas')->insert($bufferNotas);
                            $bufferNotas = [];
                        }
                    }
                }
            });

        if (! empty($bufferNotas)) {
            DB::table('notas')->insert($bufferNotas);
        }

        $this->command?->info("  → {$totalNotas} notas históricas inseridas.");
    }

    private function seedAnoActivo(AnoLectivo $ano): void
    {
        $atribuicoes = Atribuicao::where('ano_lectivo_id', $ano->id)
            ->select('id', 'turma_id', 'disciplina_id')
            ->get();

        $trimestres = Trimestre::where('ano_lectivo_id', $ano->id)
            ->orderBy('numero')
            ->get();

        $matriculasPorTurma = $this->matriculasPorTurma(['activa']);

        $ts = now();

        // 1. Avaliações: 3 tipos por trimestre por atribuição
        $this->command?->info('  → Avaliações ano corrente...');
        $buffer = [];
        foreach ($atribuicoes as $atrib) {
            foreach ($trimestres as $trim) {
                foreach (self::ACTUAL_TIPOS as $i => $tipo) {
                    $titulo = match ($tipo) {
                        'prova' => 'Prova do '.$trim->numero.'º Trimestre',
                        'teste' => 'Teste '.($i + 1).' do '.$trim->numero.'º Trimestre',
                        'avaliacao_continua' => 'Avaliação Contínua do '.$trim->numero.'º Trimestre',
                        default => 'Avaliação',
                    };
                    $peso = $tipo === 'prova' ? 2.00 : 1.00;

                    $dataAval = Carbon::parse($trim->inicio)
                        ->addDays(15 + $i * 20);

                    $buffer[] = [
                        'atribuicao_id' => $atrib->id,
                        'trimestre_id' => $trim->id,
                        'tipo' => $tipo,
                        'titulo' => $titulo,
                        'data' => $dataAval->format('Y-m-d'),
                        'peso' => $peso,
                        'max_nota' => 20.00,
                        'created_at' => $ts,
                        'updated_at' => $ts,
                    ];

                    if (count($buffer) >= 1000) {
                        DB::table('avaliacoes')->insert($buffer);
                        $buffer = [];
                    }
                }
            }
        }
        if (! empty($buffer)) {
            DB::table('avaliacoes')->insert($buffer);
        }

        // 2. Notas para todas as avaliações do ano corrente
        $this->command?->info('  → Notas ano corrente...');
        $atribIds = $atribuicoes->pluck('id')->all();
        $totalNotas = 0;
        $bufferNotas = [];

        DB::table('avaliacoes')
            ->whereIn('atribuicao_id', $atribIds)
            ->select('id', 'atribuicao_id')
            ->orderBy('id')
            ->chunk(500, function ($avals) use (
                $atribuicoes, $matriculasPorTurma,
                &$bufferNotas, &$totalNotas, $ts
            ) {
                $atribIndex = $atribuicoes->keyBy('id');
                foreach ($avals as $aval) {
                    $atrib = $atribIndex[$aval->atribuicao_id] ?? null;
                    if (! $atrib) {
                        continue;
                    }
                    $matriculas = $matriculasPorTurma[$atrib->turma_id] ?? [];
                    foreach ($matriculas as $matId) {
                        $bufferNotas[] = [
                            'avaliacao_id' => $aval->id,
                            'matricula_id' => $matId,
                            'valor' => $this->notaRealista(),
                            'observacao' => null,
                            'created_at' => $ts,
                            'updated_at' => $ts,
                        ];
                        $totalNotas++;
                        if (count($bufferNotas) >= 1500) {
                            DB::table('notas')->insert($bufferNotas);
                            $bufferNotas = [];
                        }
                    }
                }
            });
        if (! empty($bufferNotas)) {
            DB::table('notas')->insert($bufferNotas);
        }
        $this->command?->info("  → {$totalNotas} notas ano corrente inseridas.");

        // 3. Aulas + presenças no trimestre 1
        $this->command?->info('  → Aulas e presenças (trimestre 1)...');
        $trim1 = $trimestres->firstWhere('numero', 1);
        if (! $trim1) {
            return;
        }

        $semanas = $this->semanasUteis(
            Carbon::parse($trim1->inicio),
            Carbon::parse($trim1->fim),
            self::AULAS_POR_ATRIBUICAO_TRIM1
        );

        $bufferAulas = [];
        foreach ($atribuicoes as $atrib) {
            foreach ($semanas as $i => $data) {
                $bufferAulas[] = [
                    'atribuicao_id' => $atrib->id,
                    'data' => $data->format('Y-m-d'),
                    'numero' => $i + 1,
                    'hora_inicio' => '08:00:00',
                    'hora_fim' => '08:50:00',
                    'sumario' => 'Aula '.($i + 1).' — sumário a preencher.',
                    'conteudo_planeado' => null,
                    'registado_por' => null,
                    'created_at' => $ts,
                    'updated_at' => $ts,
                ];
                if (count($bufferAulas) >= 1000) {
                    DB::table('aulas')->insert($bufferAulas);
                    $bufferAulas = [];
                }
            }
        }
        if (! empty($bufferAulas)) {
            DB::table('aulas')->insert($bufferAulas);
        }

        // Presenças
        $totalPresencas = 0;
        $bufferPres = [];

        DB::table('aulas')
            ->whereIn('atribuicao_id', $atribIds)
            ->select('id', 'atribuicao_id')
            ->orderBy('id')
            ->chunk(500, function ($aulas) use (
                $atribuicoes, $matriculasPorTurma,
                &$bufferPres, &$totalPresencas, $ts
            ) {
                $atribIndex = $atribuicoes->keyBy('id');
                foreach ($aulas as $aula) {
                    $atrib = $atribIndex[$aula->atribuicao_id] ?? null;
                    if (! $atrib) {
                        continue;
                    }
                    $matriculas = $matriculasPorTurma[$atrib->turma_id] ?? [];
                    foreach ($matriculas as $matId) {
                        $r = mt_rand(1, 100);
                        $estado = match (true) {
                            $r <= 90 => 'presente',
                            $r <= 95 => 'falta',
                            $r <= 98 => 'falta_justificada',
                            default => 'atraso',
                        };
                        $bufferPres[] = [
                            'aula_id' => $aula->id,
                            'matricula_id' => $matId,
                            'estado' => $estado,
                            'observacao' => null,
                            'registado_por' => null,
                            'created_at' => $ts,
                            'updated_at' => $ts,
                        ];
                        $totalPresencas++;
                        if (count($bufferPres) >= 2000) {
                            DB::table('presencas')->insert($bufferPres);
                            $bufferPres = [];
                        }
                    }
                }
            });
        if (! empty($bufferPres)) {
            DB::table('presencas')->insert($bufferPres);
        }
        $this->command?->info("  → {$totalPresencas} presenças inseridas.");
    }

    /** @return array<int, \Illuminate\Support\Collection> ano_lectivo_id → trimestres */
    private function trimestresPorAno(): array
    {
        return Trimestre::all()
            ->groupBy('ano_lectivo_id')
            ->all();
    }

    /**
     * @param  list<string>  $estados
     * @return array<int, list<int>> turma_id → matricula_ids
     */
    private function matriculasPorTurma(array $estados): array
    {
        return DB::table('matriculas')
            ->whereIn('estado', $estados)
            ->select('id', 'turma_id')
            ->get()
            ->groupBy('turma_id')
            ->map(fn ($items) => $items->pluck('id')->all())
            ->all();
    }

    /** Distribuição realista: média ~12.5/20, std 3, clamp [4, 19.5] */
    private function notaRealista(): float
    {
        // Box-Muller
        $u1 = mt_rand(1, 10000) / 10000;
        $u2 = mt_rand(1, 10000) / 10000;
        $z = sqrt(-2 * log($u1)) * cos(2 * M_PI * $u2);
        $valor = 12.5 + 3.0 * $z;

        return round(max(4.0, min(19.5, $valor)) * 2) / 2; // step 0.5
    }

    /** @return list<Carbon> */
    private function semanasUteis(Carbon $inicio, Carbon $fim, int $maxAulas): array
    {
        $datas = [];
        $cursor = $inicio->copy()->next(Carbon::TUESDAY);
        while ($cursor->lte($fim) && count($datas) < $maxAulas) {
            $datas[] = $cursor->copy();
            $cursor->addWeek();
        }

        return $datas;
    }
}
