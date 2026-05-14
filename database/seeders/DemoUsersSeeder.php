<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\Encarregado;
use App\Models\Funcionario;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $director = User::firstOrCreate(
            ['email' => 'director@gestschool.test'],
            ['name' => 'Director Geral', 'password' => $password, 'phone' => '923000001', 'is_active' => true]
        );
        $director->syncRoles(['director_geral']);
        Funcionario::firstOrCreate(['user_id' => $director->id], [
            'numero_funcionario' => 'FUN-0001',
            'cargo' => 'Director Geral',
            'departamento' => 'Direcção',
        ]);

        $pedagogico = User::firstOrCreate(
            ['email' => 'pedagogico@gestschool.test'],
            ['name' => 'Director Pedagógico', 'password' => $password, 'phone' => '923000002', 'is_active' => true]
        );
        $pedagogico->syncRoles(['director_pedagogico']);
        Funcionario::firstOrCreate(['user_id' => $pedagogico->id], [
            'numero_funcionario' => 'FUN-0002',
            'cargo' => 'Director Pedagógico',
            'departamento' => 'Direcção Pedagógica',
        ]);

        $secretario = User::firstOrCreate(
            ['email' => 'secretario@gestschool.test'],
            ['name' => 'Maria Secretária', 'password' => $password, 'phone' => '923000003', 'is_active' => true]
        );
        $secretario->syncRoles(['secretario']);
        Funcionario::firstOrCreate(['user_id' => $secretario->id], [
            'numero_funcionario' => 'FUN-0003',
            'cargo' => 'Secretária',
            'departamento' => 'Secretaria',
        ]);

        $professor = User::firstOrCreate(
            ['email' => 'professor@gestschool.test'],
            ['name' => 'João Professor', 'password' => $password, 'phone' => '923000004', 'is_active' => true]
        );
        $professor->syncRoles(['professor']);
        Professor::firstOrCreate(['user_id' => $professor->id], [
            'numero_professor' => 'PROF-0001',
            'habilitacoes' => 'Licenciatura em Matemática',
            'especialidade' => 'Matemática',
            'disciplinas' => 'Matemática, Física',
            'assistente' => false,
        ]);

        $assistente = User::firstOrCreate(
            ['email' => 'assistente@gestschool.test'],
            ['name' => 'Ana Assistente', 'password' => $password, 'phone' => '923000005', 'is_active' => true]
        );
        $assistente->syncRoles(['professor_assistente']);
        Professor::firstOrCreate(['user_id' => $assistente->id], [
            'numero_professor' => 'PROF-0002',
            'habilitacoes' => 'Bacharelato em Letras',
            'especialidade' => 'Português',
            'disciplinas' => 'Português',
            'assistente' => true,
        ]);

        $alunoUser = User::firstOrCreate(
            ['email' => 'aluno@gestschool.test'],
            ['name' => 'Pedro Aluno', 'password' => $password, 'phone' => '923000006', 'is_active' => true]
        );
        $alunoUser->syncRoles([]);
        $aluno = Aluno::firstOrCreate(['user_id' => $alunoUser->id], [
            'numero_processo' => 'AL-2026-0001',
            'classe' => '10ª',
            'turma' => 'A',
            'ano_lectivo' => '2026/2027',
            'sexo' => 'M',
            'nacionalidade' => 'Angolana',
            'naturalidade' => 'Luanda',
        ]);

        $encarregadoUser = User::firstOrCreate(
            ['email' => 'encarregado@gestschool.test'],
            ['name' => 'Carlos Encarregado', 'password' => $password, 'phone' => '923000007', 'is_active' => true]
        );
        $encarregadoUser->syncRoles(['encarregado']);
        $encarregado = Encarregado::firstOrCreate(['user_id' => $encarregadoUser->id], [
            'profissao' => 'Engenheiro',
            'local_trabalho' => 'Empresa XYZ',
            'sexo' => 'M',
        ]);

        $aluno->encarregados()->syncWithoutDetaching([
            $encarregado->id => ['parentesco' => 'pai', 'principal' => true],
        ]);
    }
}
