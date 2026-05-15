<?php

namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Matricula;
use App\Models\Trimestre;

class BoletimService
{
    /**
     * Constrói a estrutura completa do boletim (médias por disciplina/trimestre + anual).
     *
     * @return array{matricula: Matricula, trimestres: \Illuminate\Support\Collection, medias: array}
     */
    public function build(Matricula $matricula): array
    {
        $matricula->load(['aluno.user', 'turma.classe', 'turma.curso', 'anoLectivo']);

        $trimestres = Trimestre::where('ano_lectivo_id', $matricula->ano_lectivo_id)
            ->orderBy('numero')
            ->get();

        $avaliacoes = Avaliacao::whereHas(
            'atribuicao',
            fn ($q) => $q->where('turma_id', $matricula->turma_id)
        )
            ->with([
                'atribuicao.disciplina',
                'notas' => fn ($q) => $q->where('matricula_id', $matricula->id),
            ])
            ->get();

        $resumo = [];
        foreach ($avaliacoes as $av) {
            $discId = $av->atribuicao->disciplina_id;
            $discNome = $av->atribuicao->disciplina->nome;
            $trimId = $av->trimestre_id;
            $nota = $av->notas->first()?->valor;
            if ($nota === null) {
                continue;
            }

            $resumo[$discId]['nome'] = $discNome;
            $resumo[$discId]['trimestres'][$trimId]['soma'] = ($resumo[$discId]['trimestres'][$trimId]['soma'] ?? 0) + ($nota * (float) $av->peso);
            $resumo[$discId]['trimestres'][$trimId]['peso'] = ($resumo[$discId]['trimestres'][$trimId]['peso'] ?? 0) + (float) $av->peso;
        }

        $medias = [];
        foreach ($resumo as $discId => $info) {
            $medias[$discId]['nome'] = $info['nome'];
            $somaAnual = 0;
            $countTri = 0;
            foreach ($trimestres as $t) {
                $b = $info['trimestres'][$t->id] ?? null;
                $m = $b && $b['peso'] > 0 ? round($b['soma'] / $b['peso'], 2) : null;
                $medias[$discId]['trimestres'][$t->id] = $m;
                if ($m !== null) {
                    $somaAnual += $m;
                    $countTri++;
                }
            }
            $medias[$discId]['anual'] = $countTri > 0 ? round($somaAnual / $countTri, 2) : null;
        }

        return compact('matricula', 'trimestres', 'medias');
    }

    /**
     * Resumo leve (sem detalhar por disciplina) — para listagens de histórico.
     *
     * @return array{media_anual: ?float, presencas_pct: ?float, faltas: int, total_aulas: int}
     */
    public function quickSummary(Matricula $matricula): array
    {
        $build = $this->build($matricula);

        // Média geral = média das médias anuais por disciplina
        $anuais = collect($build['medias'])->pluck('anual')->filter(fn ($v) => $v !== null);
        $mediaAnual = $anuais->isNotEmpty() ? round($anuais->avg(), 2) : null;

        // Presenças
        $totalAulas = $matricula->presencas()->count();
        $presentes = $totalAulas > 0
            ? $matricula->presencas()->whereIn('estado', ['presente', 'atraso'])->count()
            : 0;
        $faltas = $matricula->presencas()->whereIn('estado', ['falta', 'falta_justificada'])->count();

        $presencasPct = $totalAulas > 0 ? round(($presentes / $totalAulas) * 100, 1) : null;

        return [
            'media_anual' => $mediaAnual,
            'presencas_pct' => $presencasPct,
            'faltas' => $faltas,
            'total_aulas' => $totalAulas,
        ];
    }

    /**
     * Verifica se o utilizador tem acesso ao boletim de uma matrícula.
     */
    public function ensureCanView($user, Matricula $matricula): void
    {
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario', 'professor', 'professor_assistente'])) {
            return;
        }
        if ($user->hasRole('encarregado')) {
            $enc = $user->encarregado;
            abort_unless($enc && $enc->alunos()->whereKey($matricula->aluno_id)->exists(), 403);

            return;
        }
        abort(403);
    }
}
