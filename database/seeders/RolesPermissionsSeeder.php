<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // utilizadores e pessoas
            'users.view', 'users.create', 'users.update', 'users.delete',
            'professores.view', 'professores.create', 'professores.update', 'professores.delete',
            'alunos.view', 'alunos.create', 'alunos.update', 'alunos.delete',
            'encarregados.view', 'encarregados.create', 'encarregados.update', 'encarregados.delete',
            'funcionarios.view', 'funcionarios.create', 'funcionarios.update', 'funcionarios.delete',
            'meus_alunos.view',

            // estrutura académica
            'anos.view', 'anos.manage',
            'classes.view', 'classes.manage',
            'turmas.view', 'turmas.manage',
            'disciplinas.view', 'disciplinas.manage',
            'matriculas.view', 'matriculas.manage',
            'atribuicoes.view', 'atribuicoes.manage',
            'trimestres.view', 'trimestres.manage',

            // operação
            'presencas.view', 'presencas.register',
            'avaliacoes.view', 'avaliacoes.manage',
            'notas.view', 'notas.lancar',
            'pautas.view',
            'boletim.view',

            // comunicados
            'comunicados.view', 'comunicados.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $direccao = [
            'users.view', 'users.create', 'users.update', 'users.delete',
            'professores.view', 'professores.create', 'professores.update', 'professores.delete',
            'alunos.view', 'alunos.create', 'alunos.update', 'alunos.delete',
            'encarregados.view', 'encarregados.create', 'encarregados.update', 'encarregados.delete',
            'funcionarios.view', 'funcionarios.create', 'funcionarios.update', 'funcionarios.delete',
            'anos.view', 'anos.manage',
            'classes.view', 'classes.manage',
            'turmas.view', 'turmas.manage',
            'disciplinas.view', 'disciplinas.manage',
            'matriculas.view', 'matriculas.manage',
            'atribuicoes.view', 'atribuicoes.manage',
            'trimestres.view', 'trimestres.manage',
            'presencas.view', 'avaliacoes.view', 'notas.view',
            'pautas.view', 'boletim.view',
            'comunicados.view', 'comunicados.manage',
        ];

        $roles = [
            'director_geral' => $permissions,
            'director_pedagogico' => $direccao,
            'secretario' => [
                'users.view', 'users.create', 'users.update',
                'alunos.view', 'alunos.create', 'alunos.update',
                'encarregados.view', 'encarregados.create', 'encarregados.update',
                'professores.view', 'funcionarios.view',
                'anos.view', 'classes.view', 'turmas.view', 'disciplinas.view',
                'matriculas.view', 'matriculas.manage',
                'atribuicoes.view', 'trimestres.view',
                'presencas.view', 'avaliacoes.view', 'notas.view',
                'pautas.view', 'boletim.view',
                'comunicados.view',
            ],
            'professor' => [
                'alunos.view', 'encarregados.view',
                'turmas.view', 'disciplinas.view', 'trimestres.view',
                'presencas.view', 'presencas.register',
                'avaliacoes.view', 'avaliacoes.manage',
                'notas.view', 'notas.lancar',
                'pautas.view',
                'comunicados.view',
            ],
            'professor_assistente' => [
                'alunos.view',
                'turmas.view', 'disciplinas.view',
                'presencas.view', 'presencas.register',
                'comunicados.view',
            ],
            'funcionario' => ['comunicados.view'],
            'encarregado' => [
                'meus_alunos.view',
                'boletim.view',
                'comunicados.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePerms);
        }
    }
}
