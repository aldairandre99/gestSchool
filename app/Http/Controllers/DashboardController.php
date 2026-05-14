<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Encarregado;
use App\Models\Funcionario;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('encarregado')) {
            $encarregado = $user->encarregado()->with('alunos.user')->first();
            return view('dashboard.encarregado', [
                'encarregado' => $encarregado,
                'alunos' => $encarregado?->alunos ?? collect(),
            ]);
        }

        if ($user->hasAnyRole(['professor', 'professor_assistente'])) {
            return view('dashboard.professor', [
                'professor' => $user->professor,
            ]);
        }

        $stats = [
            'users' => User::count(),
            'professores' => Professor::count(),
            'alunos' => Aluno::count(),
            'encarregados' => Encarregado::count(),
            'funcionarios' => Funcionario::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}
