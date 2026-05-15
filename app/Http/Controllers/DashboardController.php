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
            $encarregado = $user->encarregado()
                ->with([
                    'alunos.user',
                    'alunos.matriculas.turma.classe',
                    'alunos.matriculas.anoLectivo',
                ])
                ->first();

            $alunos = $encarregado?->alunos ?? collect();

            // Alertas por aluno: notas baixas (<10) e faltas recentes (≥3 últimos 30 dias)
            $alertas = [];
            $diasAlerta = now()->subDays(30);

            foreach ($alunos as $aluno) {
                $matriculaActiva = $aluno->matriculas->firstWhere('estado', 'activa');
                if (! $matriculaActiva) {
                    continue;
                }

                $notasBaixas = \App\Models\Nota::where('matricula_id', $matriculaActiva->id)
                    ->where('valor', '<', 10)
                    ->whereNotNull('valor')
                    ->with(['avaliacao.atribuicao.disciplina', 'avaliacao.trimestre'])
                    ->orderByDesc('id')
                    ->take(3)
                    ->get();

                $faltasRecentes = \App\Models\Presenca::where('matricula_id', $matriculaActiva->id)
                    ->whereIn('estado', ['falta', 'falta_justificada'])
                    ->whereHas('aula', fn ($q) => $q->where('data', '>=', $diasAlerta))
                    ->count();

                if ($notasBaixas->isNotEmpty() || $faltasRecentes >= 3) {
                    $alertas[$aluno->id] = [
                        'aluno' => $aluno,
                        'matricula' => $matriculaActiva,
                        'notas_baixas' => $notasBaixas,
                        'faltas_recentes' => $faltasRecentes,
                    ];
                }
            }

            return view('dashboard.encarregado', compact('encarregado', 'alunos', 'alertas'));
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
