<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class FuncionarioController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $funcionarios = Funcionario::with('user.roles')
            ->when($q, fn ($query) => $query->whereHas('user', function ($w) use ($q) {
                $w->where('name', 'ilike', "%$q%")->orWhere('email', 'ilike', "%$q%");
            }))
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('funcionarios.index', compact('funcionarios', 'q'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['director_geral', 'director_pedagogico', 'secretario', 'funcionario'])->get();
        return view('funcionarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);
        $user->syncRoles([$data['role']]);

        Funcionario::create([
            'user_id' => $user->id,
            'numero_funcionario' => $data['numero_funcionario'] ?? null,
            'bi' => $data['bi'] ?? null,
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'sexo' => $data['sexo'] ?? null,
            'cargo' => $data['cargo'] ?? null,
            'departamento' => $data['departamento'] ?? null,
            'data_admissao' => $data['data_admissao'] ?? null,
            'morada' => $data['morada'] ?? null,
        ]);

        return redirect()->route('funcionarios.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Funcionario $funcionario)
    {
        $funcionario->load('user.roles');
        return view('funcionarios.show', compact('funcionario'));
    }

    public function edit(Funcionario $funcionario)
    {
        $funcionario->load('user.roles');
        $roles = Role::whereIn('name', ['director_geral', 'director_pedagogico', 'secretario', 'funcionario'])->get();
        return view('funcionarios.edit', compact('funcionario', 'roles'));
    }

    public function update(Request $request, Funcionario $funcionario)
    {
        $data = $this->validateData($request, $funcionario);

        $funcionario->user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);
        if (! empty($data['password'])) {
            $funcionario->user->password = Hash::make($data['password']);
        }
        $funcionario->user->save();
        $funcionario->user->syncRoles([$data['role']]);

        $funcionario->update([
            'numero_funcionario' => $data['numero_funcionario'] ?? null,
            'bi' => $data['bi'] ?? null,
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'sexo' => $data['sexo'] ?? null,
            'cargo' => $data['cargo'] ?? null,
            'departamento' => $data['departamento'] ?? null,
            'data_admissao' => $data['data_admissao'] ?? null,
            'morada' => $data['morada'] ?? null,
        ]);

        return redirect()->route('funcionarios.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Funcionario $funcionario)
    {
        $user = $funcionario->user;
        $funcionario->delete();
        $user?->delete();
        return redirect()->route('funcionarios.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function validateData(Request $request, ?Funcionario $funcionario = null): array
    {
        $userId = $funcionario?->user_id;
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [$funcionario ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['director_geral', 'director_pedagogico', 'secretario', 'funcionario'])],
            'numero_funcionario' => ['nullable', 'string', 'max:30', Rule::unique('funcionarios', 'numero_funcionario')->ignore($funcionario?->id)],
            'bi' => ['nullable', 'string', 'max:30'],
            'data_nascimento' => ['nullable', 'date'],
            'sexo' => ['nullable', 'in:M,F'],
            'cargo' => ['nullable', 'string', 'max:100'],
            'departamento' => ['nullable', 'string', 'max:100'],
            'data_admissao' => ['nullable', 'date'],
            'morada' => ['nullable', 'string'],
        ]);
    }
}
