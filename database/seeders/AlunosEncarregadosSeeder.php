<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Encarregado;
use App\Models\Turma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AlunosEncarregadosSeeder extends Seeder
{
    private const TOTAL_ALUNOS = 3000;

    private const CAPACIDADE_TURMA = 38; // matriculas por turma (capacidade real é 40)

    /** @var array<int, array{ano: int, classe_inicio: int, anos_presenca: int, sexo: string, curso_preferido: ?string}> */
    private array $perfis = [];

    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_PT');
        $faker->seed(20260517);

        $anos = AnoLectivo::orderBy('codigo')->get()->keyBy(fn ($a) => (int) substr($a->codigo, 0, 4));
        $turmasIndex = $this->indexarTurmas();

        $passwordHash = Hash::make('password');
        $encarregadoRoleId = Role::where('name', 'encarregado')->where('guard_name', 'web')->value('id');

        $this->command?->info('A gerar perfis de alunos...');
        $this->gerarPerfis($faker);

        $this->command?->info('A criar alunos, encarregados e matrículas...');

        // Pool de encarregados partilháveis (~30% dos alunos partilham com irmão)
        $encarregadosPool = [];

        $contadorMatricula = [];
        $contadorAlunoPorAno = [];

        DB::transaction(function () use (
            $faker, $anos, $turmasIndex, $passwordHash,
            $encarregadoRoleId, &$encarregadosPool,
            &$contadorMatricula, &$contadorAlunoPorAno
        ) {
            foreach ($this->perfis as $idx => $perfil) {
                $anoInicio = $perfil['ano'];
                $classeInicio = $perfil['classe_inicio'];
                $sexo = $perfil['sexo'];
                $cursoPref = $perfil['curso_preferido'];

                // Numero processo formato AL-YY-XXXX (sequencial dentro do ano de início)
                $yy = substr((string) $anoInicio, 2, 2);
                $contadorAlunoPorAno[$anoInicio] = ($contadorAlunoPorAno[$anoInicio] ?? 0) + 1;
                $numeroProcesso = sprintf('AL-%s-%04d', $yy, $contadorAlunoPorAno[$anoInicio]);

                $idadeAtClasse1 = 6;
                $anosNasc = $anoInicio - $idadeAtClasse1 - ($classeInicio - 1) + $faker->numberBetween(-1, 1);
                $dataNasc = Carbon::create($anosNasc, $faker->numberBetween(1, 12), $faker->numberBetween(1, 28));

                $nome = $this->nomeAluno($faker, $sexo);
                $email = sprintf('aluno.%s%d@gestschool.test', \Illuminate\Support\Str::slug(explode(' ', $nome)[0], ''), $idx + 100);

                $user = User::create([
                    'name' => $nome,
                    'email' => $email,
                    'password' => $passwordHash,
                    'phone' => '9'.$faker->numerify('########'),
                    'is_active' => true,
                ]);

                $aluno = Aluno::create([
                    'user_id' => $user->id,
                    'numero_processo' => $numeroProcesso,
                    'bi' => $faker->numerify('#########').strtoupper($faker->bothify('??###')),
                    'data_nascimento' => $dataNasc->format('Y-m-d'),
                    'sexo' => $sexo,
                    'nacionalidade' => 'Angolana',
                    'naturalidade' => $faker->randomElement(['Luanda', 'Benguela', 'Huambo', 'Lubango', 'Cabinda', 'Malanje', 'Uíge', 'Namibe']),
                    'morada' => $this->morada($faker),
                ]);

                // Encarregado: 70% novo, 30% partilhado (irmãos)
                if (! empty($encarregadosPool) && $faker->boolean(30)) {
                    $encarregado = $faker->randomElement($encarregadosPool);
                } else {
                    $encarregado = $this->criarEncarregado($faker, $passwordHash, $encarregadoRoleId);
                    $encarregadosPool[] = $encarregado;
                    if (count($encarregadosPool) > 200) {
                        array_shift($encarregadosPool);
                    }
                }

                DB::table('aluno_encarregado')->insert([
                    'aluno_id' => $aluno->id,
                    'encarregado_id' => $encarregado->id,
                    'parentesco' => $faker->randomElement(['pai', 'mae', 'mae', 'tutor']),
                    'principal' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Matrículas históricas
                $classeAtual = $classeInicio;
                for ($n = 0; $n < $perfil['anos_presenca']; $n++) {
                    $anoLectivo = $anoInicio + $n;
                    if (! isset($anos[$anoLectivo])) {
                        break;
                    }
                    if ($classeAtual > 13) {
                        break; // já se formou
                    }

                    $ano = $anos[$anoLectivo];
                    $cursoSigla = $classeAtual >= 10 ? $cursoPref : null;
                    if ($cursoSigla === 'INF' && $classeAtual > 13) {
                        break;
                    }
                    if ($cursoSigla !== 'INF' && $cursoSigla !== null && $classeAtual > 12) {
                        break; // cursos não-INF acabam na 12ª
                    }

                    $turma = $this->escolherTurma(
                        $turmasIndex, $ano->id, $classeAtual, $cursoSigla, $faker
                    );
                    if (! $turma) {
                        break; // sem vagas
                    }

                    $contadorMatricula[$anoLectivo] = ($contadorMatricula[$anoLectivo] ?? 0) + 1;
                    $numMat = sprintf('M-%d-%05d', $anoLectivo, $contadorMatricula[$anoLectivo]);

                    $isUltimoAnoPresenca = ($n === $perfil['anos_presenca'] - 1);
                    $isAnoActivo = ($ano->codigo === '2026/2027');

                    $estado = match (true) {
                        $isAnoActivo => 'activa',
                        $isUltimoAnoPresenca && $faker->boolean(20) => $faker->randomElement(['transferido', 'desistente', 'reprovado']),
                        default => 'aprovado',
                    };

                    DB::table('matriculas')->insert([
                        'aluno_id' => $aluno->id,
                        'turma_id' => $turma->id,
                        'ano_lectivo_id' => $ano->id,
                        'numero_matricula' => $numMat,
                        'data_matricula' => $ano->inicio,
                        'estado' => $estado,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (in_array($estado, ['reprovado'], true)) {
                        // não avança classe
                    } else {
                        $classeAtual++;
                    }

                    if (in_array($estado, ['transferido', 'desistente'], true)) {
                        break;
                    }
                }
            }
        });

        $this->command?->info(sprintf(
            'Alunos: %d | Encarregados: %d | Matrículas: %d',
            Aluno::count(),
            Encarregado::count(),
            DB::table('matriculas')->count()
        ));
    }

    private function gerarPerfis(\Faker\Generator $faker): void
    {
        // Distribuição do ano de início (ano = primeiro ano lectivo em que apareceu).
        // Equilibrada para garantir que ~65% dos alunos chegam ao ano corrente (2026/2027).
        $distAno = [
            2022 => 0.30,
            2023 => 0.20,
            2024 => 0.18,
            2025 => 0.17,
            2026 => 0.15,
        ];

        $cursos = ['INF', 'GRH', 'HOT', 'CG', 'FB'];

        // Pesos por classe inicial (pirâmide invertida: mais novos nas classes baixas)
        $classesPesos = [
            1 => 14, 2 => 13, 3 => 12, 4 => 11, 5 => 10, 6 => 9,
            7 => 8, 8 => 7, 9 => 6,
            10 => 5, 11 => 3, 12 => 2,
        ];

        for ($i = 0; $i < self::TOTAL_ALUNOS; $i++) {
            // ano de início
            $r = mt_rand(1, 100) / 100;
            $cum = 0;
            $ano = 2022;
            foreach ($distAno as $a => $p) {
                $cum += $p;
                if ($r <= $cum) {
                    $ano = $a;
                    break;
                }
            }

            $classeInicio = $this->pesoAleatorio($classesPesos);

            // anos de presença: viés para chegar até hoje
            // - 65% percorre tudo até ao ano corrente (ou até 13ª, o que vier primeiro)
            // - 25% sai a meio (2 a max-1 anos)
            // - 10% só passou um ano lectivo
            $maxAtéHoje = 2027 - $ano;             // ex: começou em 2022 → 5 anos até 2027
            $maxAtéFormar = 13 - $classeInicio + 1; // ex: classeInicio=10 → 4 anos até 13ª
            $maxPossivel = max(1, min($maxAtéHoje, $maxAtéFormar));

            $tipo = mt_rand(1, 100);
            if ($tipo <= 65) {
                $anosPresenca = $maxPossivel;
            } elseif ($tipo <= 90) {
                $anosPresenca = $maxPossivel >= 3
                    ? $faker->numberBetween(2, $maxPossivel - 1)
                    : $maxPossivel;
            } else {
                $anosPresenca = min($maxPossivel, $faker->numberBetween(1, 2));
            }

            $this->perfis[] = [
                'ano' => $ano,
                'classe_inicio' => $classeInicio,
                'anos_presenca' => $anosPresenca,
                'sexo' => $faker->randomElement(['M', 'F']),
                'curso_preferido' => $faker->randomElement($cursos),
            ];
        }
    }

    /** @return array<int, array<string, array<string, list<object>>>> [ano_id][classeNome][cursoSigla|'-'] */
    private function indexarTurmas(): array
    {
        $turmas = Turma::with('classe', 'curso')->get();
        $idx = [];
        foreach ($turmas as $t) {
            $key = $t->curso?->sigla ?? '-';
            $idx[$t->ano_lectivo_id][$t->classe->nome][$key][] = (object) [
                'id' => $t->id,
                'ocupacao' => 0,
            ];
        }

        return $idx;
    }

    private function escolherTurma(
        array &$idx,
        int $anoLectivoId,
        int $classeOrdem,
        ?string $cursoSigla,
        \Faker\Generator $faker
    ): ?object {
        static $classeNomes = [
            1 => '1ª', 2 => '2ª', 3 => '3ª', 4 => '4ª', 5 => '5ª',
            6 => '6ª', 7 => '7ª', 8 => '8ª', 9 => '9ª',
            10 => '10ª', 11 => '11ª', 12 => '12ª', 13 => '13ª',
        ];

        $classeNome = $classeNomes[$classeOrdem] ?? null;
        if (! $classeNome) {
            return null;
        }
        $key = $cursoSigla ?? '-';
        $candidatas = $idx[$anoLectivoId][$classeNome][$key] ?? [];

        if (empty($candidatas)) {
            // se não há turma para este curso, tentar qualquer outra do médio
            if ($cursoSigla !== null) {
                $todasMedio = $idx[$anoLectivoId][$classeNome] ?? [];
                foreach ($todasMedio as $k => $list) {
                    if ($k !== '-') {
                        $candidatas = array_merge($candidatas, $list);
                    }
                }
            }
        }

        // Filtrar com vaga
        $comVaga = array_filter($candidatas, fn ($t) => $t->ocupacao < self::CAPACIDADE_TURMA);
        if (empty($comVaga)) {
            return null;
        }

        $escolhida = $comVaga[array_rand($comVaga)];
        $escolhida->ocupacao++;

        return $escolhida;
    }

    private function pesoAleatorio(array $pesos): int
    {
        $total = array_sum($pesos);
        $r = mt_rand(1, $total);
        $cum = 0;
        foreach ($pesos as $valor => $peso) {
            $cum += $peso;
            if ($r <= $cum) {
                return $valor;
            }
        }

        return array_key_first($pesos);
    }

    private function criarEncarregado(
        \Faker\Generator $faker,
        string $passwordHash,
        ?int $roleId
    ): Encarregado {
        static $contador = 0;
        $contador++;

        $sexo = $faker->randomElement(['M', 'F']);
        $nome = $this->nomeAdulto($faker, $sexo);
        $email = sprintf('enc.%s%d@gestschool.test', \Illuminate\Support\Str::slug(explode(' ', $nome)[0], ''), $contador);

        $user = User::create([
            'name' => $nome,
            'email' => $email,
            'password' => $passwordHash,
            'phone' => '9'.$faker->numerify('########'),
            'is_active' => true,
        ]);

        if ($roleId) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => User::class,
                'model_id' => $user->id,
            ]);
        }

        return Encarregado::create([
            'user_id' => $user->id,
            'bi' => $faker->numerify('#########').strtoupper($faker->bothify('??###')),
            'data_nascimento' => Carbon::now()->subYears($faker->numberBetween(28, 60))->format('Y-m-d'),
            'sexo' => $sexo,
            'profissao' => $faker->randomElement([
                'Comerciante', 'Engenheiro', 'Médico', 'Enfermeiro', 'Professor',
                'Funcionário Público', 'Empresário', 'Técnico', 'Motorista',
                'Polícia', 'Bancário', 'Costureira', 'Cozinheiro', 'Jornalista',
            ]),
            'local_trabalho' => $faker->company,
            'morada' => $this->morada($faker),
        ]);
    }

    private function nomeAluno(\Faker\Generator $faker, string $sexo): string
    {
        $primeirosM = ['João', 'Pedro', 'Mateus', 'Hélder', 'Adriano', 'Bento',
            'Sérgio', 'Tomás', 'Vasco', 'Miguel', 'Rui', 'Nelson', 'Mário',
            'Filipe', 'Edmilson', 'Domingos', 'Eduardo', 'Adilson', 'Yuri',
            'Kelson', 'Manuel', 'Alfredo', 'Augusto'];
        $primeirosF = ['Maria', 'Ana', 'Joana', 'Carolina', 'Beatriz', 'Marta',
            'Cláudia', 'Filomena', 'Esperança', 'Domingas', 'Lúcia', 'Yara',
            'Patrícia', 'Sandra', 'Helena', 'Laura', 'Vanessa', 'Sara',
            'Raquel', 'Gisela', 'Wilma', 'Stela'];

        $primeiro = $sexo === 'M'
            ? $faker->randomElement($primeirosM)
            : $faker->randomElement($primeirosF);

        return $primeiro.' '.$this->apelido($faker).' '.$this->apelido($faker);
    }

    private function nomeAdulto(\Faker\Generator $faker, string $sexo): string
    {
        return $this->nomeAluno($faker, $sexo);
    }

    private function apelido(\Faker\Generator $faker): string
    {
        return $faker->randomElement([
            'Bumba', 'Cabral', 'Calundungo', 'Capingana', 'Chiteculo', 'Diogo',
            'dos Santos', 'Fernandes', 'Francisco', 'Gomes', 'Gonçalves',
            'Kiala', 'Kassoma', 'Lopes', 'Mateus', 'Mendes', 'Mukenge',
            'Mulando', 'Nascimento', 'Neto', 'Nunes', 'Pereira', 'Quissanga',
            'Sapalo', 'Sebastião', 'Silva', 'Sousa', 'Tati', 'Tchipalavela',
            'Vunge', 'Wassuka', 'Tchikuteni', 'Mbumba', 'Catitas',
        ]);
    }

    private function morada(\Faker\Generator $faker): string
    {
        $bairros = ['Maianga', 'Ingombota', 'Rangel', 'Sambizanga', 'Cazenga',
            'Viana', 'Kilamba', 'Talatona', 'Benfica', 'Cacuaco', 'Camama',
            'Golf 2', 'Vila Alice', 'Prenda', 'Bairro Operário'];

        return sprintf('Rua %s, n.º %d, Bairro %s, Luanda',
            $faker->lastName,
            $faker->numberBetween(1, 350),
            $faker->randomElement($bairros)
        );
    }
}
