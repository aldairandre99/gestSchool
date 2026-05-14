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
            'users.view', 'users.create', 'users.update', 'users.delete',
            'professores.view', 'professores.create', 'professores.update', 'professores.delete',
            'alunos.view', 'alunos.create', 'alunos.update', 'alunos.delete',
            'encarregados.view', 'encarregados.create', 'encarregados.update', 'encarregados.delete',
            'funcionarios.view', 'funcionarios.create', 'funcionarios.update', 'funcionarios.delete',
            'meus_alunos.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $roles = [
            'director_geral' => $permissions,
            'director_pedagogico' => [
                'users.view', 'users.create', 'users.update',
                'professores.view', 'professores.create', 'professores.update',
                'alunos.view', 'alunos.create', 'alunos.update',
                'encarregados.view', 'encarregados.create', 'encarregados.update',
                'funcionarios.view',
            ],
            'secretario' => [
                'users.view', 'users.create', 'users.update',
                'alunos.view', 'alunos.create', 'alunos.update',
                'encarregados.view', 'encarregados.create', 'encarregados.update',
                'professores.view',
                'funcionarios.view',
            ],
            'professor' => [
                'alunos.view',
                'encarregados.view',
            ],
            'professor_assistente' => [
                'alunos.view',
            ],
            'funcionario' => [],
            'encarregado' => [
                'meus_alunos.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePerms);
        }
    }
}
