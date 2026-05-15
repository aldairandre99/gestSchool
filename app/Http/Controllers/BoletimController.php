<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Avaliacao;
use App\Models\Matricula;
use App\Models\Trimestre;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BoletimController extends Controller
{
    public function show(Request $request, Matricula $matricula)
    {
        $this->ensureCanView($request->user(), $matricula);
        return view('boletim.show', $this->build($matricula));
    }

    public function pdf(Request $request, Matricula $matricula)
    {
        $this->ensureCanView($request->user(), $matricula);
        $data = $this->build($matricula);
        $filename = sprintf('boletim-%s-%s.pdf',
            str($matricula->numero_matricula)->slug(),
            str($matricula->anoLectivo->codigo)->slug()
        );
        return Pdf::loadView('pdf.boletim.show', $data)->setPaper('a4', 'portrait')->download($filename);
    }

    protected function build(Matricula $matricula): array
    {
        $matricula->load(['aluno.user', 'turma.classe', 'turma.curso', 'anoLectivo']);

        $trimestres = Trimestre::where('ano_lectivo_id', $matricula->ano_lectivo_id)
            ->orderBy('numero')->get();

        $avaliacoes = Avaliacao::whereHas('atribuicao', fn ($q) => $q->where('turma_id', $matricula->turma_id))
            ->with(['atribuicao.disciplina', 'notas' => fn ($q) => $q->where('matricula_id', $matricula->id)])
            ->get();

        $resumo = [];
        foreach ($avaliacoes as $av) {
            $discId = $av->atribuicao->disciplina_id;
            $discNome = $av->atribuicao->disciplina->nome;
            $trimId = $av->trimestre_id;
            $nota = $av->notas->first()?->valor;
            if ($nota === null) continue;

            $resumo[$discId]['nome'] = $discNome;
            $resumo[$discId]['trimestres'][$trimId]['soma'] = ($resumo[$discId]['trimestres'][$trimId]['soma'] ?? 0) + ($nota * (float) $av->peso);
            $resumo[$discId]['trimestres'][$trimId]['peso'] = ($resumo[$discId]['trimestres'][$trimId]['peso'] ?? 0) + (float) $av->peso;
        }

        $medias = [];
        foreach ($resumo as $discId => $info) {
            $medias[$discId]['nome'] = $info['nome'];
            $somaAnual = 0; $countTri = 0;
            foreach ($trimestres as $t) {
                $b = $info['trimestres'][$t->id] ?? null;
                $m = $b && $b['peso'] > 0 ? round($b['soma'] / $b['peso'], 2) : null;
                $medias[$discId]['trimestres'][$t->id] = $m;
                if ($m !== null) { $somaAnual += $m; $countTri++; }
            }
            $medias[$discId]['anual'] = $countTri > 0 ? round($somaAnual / $countTri, 2) : null;
        }

        return compact('matricula', 'trimestres', 'medias');
    }

    protected function ensureCanView($user, Matricula $matricula): void
    {
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario', 'professor', 'professor_assistente'])) return;
        if ($user->hasRole('encarregado')) {
            $enc = $user->encarregado;
            abort_unless($enc && $enc->alunos()->whereKey($matricula->aluno_id)->exists(), 403);
            return;
        }
        abort(403);
    }
}
