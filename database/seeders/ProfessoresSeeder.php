<?php

namespace Database\Seeders;

use App\Models\Professor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfessoresSeeder extends Seeder
{
    /**
     * Mapa especialidade → siglas das disciplinas que pode leccionar.
     * Cobertura completa de todas as disciplinas seed do AcademicSeeder.
     */
    private array $especialidades = [
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

    /** Distribuição desejada: especialidade → quantos professores com ela */
    private array $distribuicao = [
        'Língua Portuguesa' => 5,
        'Matemática' => 5,
        'Inglês' => 3,
        'Francês' => 1,
        'Educação Física' => 3,
        'História' => 2,
        'Geografia' => 2,
        'Física' => 2,
        'Química' => 2,
        'Biologia' => 2,
        'Filosofia' => 1,
        'Sociologia' => 1,
        'Psicologia' => 1,
        'Economia' => 2,
        'Direito' => 2,
        'Contabilidade' => 3,
        'Gestão' => 2,
        'Recursos Humanos' => 2,
        'Informática' => 3,
        'Engenharia de Software' => 1,
        'Redes e Sistemas' => 1,
        'Cozinha e Pastelaria' => 2,
        'Hotelaria' => 1,
        'Ensino Primário' => 4,
    ];

    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_PT');
        $faker->seed(20260515);

        $contador = Professor::max('id') ?? 0;
        $contador = max($contador, 2); // PROF-0001 e PROF-0002 são demos

        foreach ($this->distribuicao as $especialidade => $quantos) {
            $siglas = $this->especialidades[$especialidade];

            for ($i = 0; $i < $quantos; $i++) {
                $contador++;
                $sexo = $faker->randomElement(['M', 'F']);
                $nome = $this->nomeAngolano($faker, $sexo);
                $email = sprintf(
                    'prof.%s%d@gestschool.test',
                    \Illuminate\Support\Str::slug(explode(' ', $nome)[0], ''),
                    $contador
                );

                if (User::where('email', $email)->exists()) {
                    continue;
                }

                $user = User::create([
                    'name' => $nome,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'phone' => '9'.$faker->numerify('########'),
                    'is_active' => true,
                ]);
                $user->syncRoles(['professor']);

                $assistente = $faker->boolean(10);
                $anoAdmissao = $faker->numberBetween(2018, 2026);

                Professor::create([
                    'user_id' => $user->id,
                    'numero_professor' => sprintf('PROF-%04d', $contador),
                    'bi' => $faker->numerify('#########').strtoupper($faker->bothify('??###')),
                    'data_nascimento' => Carbon::now()->subYears($faker->numberBetween(28, 60))->format('Y-m-d'),
                    'sexo' => $sexo,
                    'habilitacoes' => $this->habilitacao($especialidade),
                    'especialidade' => $especialidade,
                    'disciplinas' => implode(', ', $siglas),
                    'data_admissao' => Carbon::create($anoAdmissao, $faker->numberBetween(1, 10), $faker->numberBetween(1, 28))->format('Y-m-d'),
                    'assistente' => $assistente,
                    'morada' => $this->morada($faker),
                ]);
            }
        }
    }

    private function nomeAngolano(\Faker\Generator $faker, string $sexo): string
    {
        $primeirosM = ['João', 'Pedro', 'António', 'Manuel', 'Mateus', 'Lúcio', 'Hélder',
            'Domingos', 'Alfredo', 'Carlos', 'Eduardo', 'Filipe', 'Augusto', 'Bento',
            'Adriano', 'Nelson', 'Mário', 'Rui', 'Sérgio', 'Tomás', 'Vasco', 'Miguel'];
        $primeirosF = ['Maria', 'Ana', 'Joana', 'Filomena', 'Esperança', 'Domingas',
            'Lúcia', 'Cláudia', 'Marta', 'Beatriz', 'Carolina', 'Isabel', 'Helena',
            'Lurdes', 'Patrícia', 'Sandra', 'Teresa', 'Vitória', 'Yara', 'Zita'];
        $apelidos = ['Bumba', 'Cabral', 'Calundungo', 'Capingana', 'Chiteculo', 'Diogo',
            'dos Santos', 'Fernandes', 'Francisco', 'Gomes', 'Gonçalves', 'Kiala',
            'Kassoma', 'Lopes', 'Mateus', 'Mendes', 'Mukenge', 'Mulando', 'Nascimento',
            'Neto', 'Nunes', 'Pereira', 'Quissanga', 'Sapalo', 'Sebastião', 'Silva',
            'Sousa', 'Tati', 'Tchipalavela', 'Vunge', 'Wassuka'];

        $primeiro = $sexo === 'M'
            ? $faker->randomElement($primeirosM)
            : $faker->randomElement($primeirosF);

        return $primeiro.' '.$faker->randomElement($apelidos).' '.$faker->randomElement($apelidos);
    }

    private function habilitacao(string $esp): string
    {
        return match (true) {
            in_array($esp, ['Engenharia de Software', 'Redes e Sistemas']) => "Licenciatura em Engenharia Informática",
            $esp === 'Informática' => "Licenciatura em {$esp}",
            $esp === 'Ensino Primário' => 'Bacharelato em Ensino Primário',
            $esp === 'Cozinha e Pastelaria' => 'Curso Técnico Profissional em Cozinha',
            $esp === 'Hotelaria' => 'Licenciatura em Gestão Hoteleira',
            default => "Licenciatura em {$esp}",
        };
    }

    private function morada(\Faker\Generator $faker): string
    {
        $bairros = ['Maianga', 'Ingombota', 'Rangel', 'Sambizanga', 'Cazenga', 'Viana',
            'Kilamba', 'Talatona', 'Benfica', 'Cacuaco', 'Camama'];

        return sprintf(
            'Rua %s, n.º %d, Bairro %s, Luanda',
            $faker->lastName,
            $faker->numberBetween(1, 350),
            $faker->randomElement($bairros)
        );
    }
}
