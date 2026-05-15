<?php

namespace App\Http\Controllers;

use App\Models\Atribuicao;
use App\Models\Avaliacao;
use App\Models\Matricula;
use App\Models\Trimestre;
use Illuminate\Http\Request;

class PautaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $atribuicoes = Atribuicao::with(['turma.classe', 'disciplina', 'anoLectivo'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) $q->where('professor_id', $prof->id);
            })
            ->get();

        $trimestres = Trimestre::with('anoLectivo')->orderBy('ano_lectivo_id', 'desc')->orderBy('numero')->get();

        return view('pautas.index', compact('atribuicoes', 'trimestres'));
    }

    public function show(Request $request, Atribuicao $atribuicao, Trimestre $trimestre)
    {
        $atribuicao->load(['turma.classe', 'disciplina']);

        $avaliacoes = Avaliacao::where('atribuicao_id', $atribuicao->id)
            ->where('trimestre_id', $trimestre->id)
            ->with('notas')
            ->orderBy('data')
            ->get();

        $matriculas = Matricula::with('aluno.user')
            ->where('turma_id', $atribuicao->turma_id)
            ->where('ano_lectivo_id', $atribuicao->ano_lectivo_id)
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->aluno->user->name);

        $notasMap = [];
        foreach ($avaliacoes as $av) {
            foreach ($av->notas as $n) {
                $notasMap[$n->matricula_id][$av->id] = $n->valor;
            }
        }

        $medias = [];
        foreach ($matriculas as $m) {
            $somaPesos = 0; $somaPond = 0;
            foreach ($avaliacoes as $av) {
                $valor = $notasMap[$m->id][$av->id] ?? null;
                if ($valor === null) continue;
                $somaPond += $valor * (float) $av->peso;
                $somaPesos += (float) $av->peso;
            }
            $medias[$m->id] = $somaPesos > 0 ? round($somaPond / $somaPesos, 2) : null;
        }

        return view('pautas.show', compact('atribuicao', 'trimestre', 'avaliacoes', 'matriculas', 'notasMap', 'medias'));
    }
}
