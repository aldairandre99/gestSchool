<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Atribuicao;
use App\Models\Avaliacao;
use App\Models\Matricula;
use App\Models\Trimestre;
use App\Models\Turma;
use App\Services\PautaCalculator;
use Illuminate\Http\Request;

class PautaController extends Controller
{
    public function index(Request $request)
    {
        $anos = AnoLectivo::orderBy('codigo', 'desc')->get();
        $turmas = Turma::with(['classe', 'curso', 'anoLectivo'])->orderBy('classe_id')->orderBy('nome')->get();
        $trimestres = Trimestre::with('anoLectivo')->orderBy('numero')->get();

        $user = $request->user();
        $atribuicoes = Atribuicao::with(['turma.classe', 'disciplina', 'anoLectivo'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) $q->where('professor_id', $prof->id);
            })
            ->get();

        return view('pautas.index', compact('anos', 'turmas', 'trimestres', 'atribuicoes'));
    }

    /** MODO 1 — Pauta por disciplina + trimestre */
    public function disciplina(Request $request, Atribuicao $atribuicao, Trimestre $trimestre)
    {
        $atribuicao->load(['turma.classe', 'turma.curso', 'disciplina']);

        $avaliacoes = Avaliacao::where('atribuicao_id', $atribuicao->id)
            ->where('trimestre_id', $trimestre->id)
            ->with('notas')
            ->orderBy('data')->get();

        $matriculas = $this->matriculasDaTurma($atribuicao->turma_id, $atribuicao->ano_lectivo_id);

        $notasMap = [];
        foreach ($avaliacoes as $av) {
            foreach ($av->notas as $n) {
                $notasMap[$n->matricula_id][$av->id] = $n->valor;
            }
        }

        $calc = new PautaCalculator();
        $medias = [];
        foreach ($matriculas as $m) {
            $itens = [];
            foreach ($avaliacoes as $av) {
                $itens[] = ['valor' => $notasMap[$m->id][$av->id] ?? null, 'peso' => $av->peso];
            }
            $medias[$m->id] = $calc->mediaTrimestre($itens);
        }

        return view('pautas.disciplina', compact('atribuicao', 'trimestre', 'avaliacoes', 'matriculas', 'notasMap', 'medias', 'calc'));
    }

    /** MODO 2 — Pauta da turma no trimestre (todas as disciplinas × alunos) */
    public function turmaTrimestre(Request $request, Turma $turma, Trimestre $trimestre)
    {
        $turma->load(['classe', 'curso', 'anoLectivo']);

        $atribuicoes = Atribuicao::with('disciplina')
            ->where('turma_id', $turma->id)
            ->where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->get();

        $matriculas = $this->matriculasDaTurma($turma->id, $turma->ano_lectivo_id);
        $calc = new PautaCalculator();

        $todasAvaliacoes = Avaliacao::whereIn('atribuicao_id', $atribuicoes->pluck('id'))
            ->where('trimestre_id', $trimestre->id)
            ->with('notas')
            ->get()
            ->groupBy('atribuicao_id');

        $mediasTurma = [];
        foreach ($matriculas as $m) {
            foreach ($atribuicoes as $atr) {
                $avs = $todasAvaliacoes[$atr->id] ?? collect();
                $itens = $avs->map(fn ($av) => [
                    'valor' => $av->notas->firstWhere('matricula_id', $m->id)?->valor,
                    'peso' => $av->peso,
                ])->all();
                $mediasTurma[$m->id][$atr->disciplina_id] = $calc->mediaTrimestre($itens);
            }
        }

        $mediaGeral = [];
        foreach ($matriculas as $m) {
            $mediaGeral[$m->id] = $calc->mediaGeral($mediasTurma[$m->id] ?? []);
        }

        return view('pautas.turma-trimestre', compact('turma', 'trimestre', 'atribuicoes', 'matriculas', 'mediasTurma', 'mediaGeral', 'calc'));
    }

    /** MODO 3 — Pauta anual da turma */
    public function turmaAnual(Request $request, Turma $turma)
    {
        $pesos = $this->pesosFromRequest($request);
        $calc = new PautaCalculator($pesos);

        $turma->load(['classe', 'curso', 'anoLectivo']);

        $atribuicoes = Atribuicao::with('disciplina')
            ->where('turma_id', $turma->id)
            ->where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->get();

        $trimestres = Trimestre::where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->orderBy('numero')->get();

        $matriculas = $this->matriculasDaTurma($turma->id, $turma->ano_lectivo_id);

        $todasAvaliacoes = Avaliacao::whereIn('atribuicao_id', $atribuicoes->pluck('id'))
            ->with('notas')
            ->get()
            ->groupBy(['atribuicao_id', 'trimestre_id']);

        $mediasAnuais = [];
        $mediasPorTrimestre = [];

        foreach ($matriculas as $m) {
            foreach ($atribuicoes as $atr) {
                $mediasTrim = [];
                foreach ($trimestres as $t) {
                    $avs = $todasAvaliacoes[$atr->id][$t->id] ?? collect();
                    $itens = $avs->map(fn ($av) => [
                        'valor' => $av->notas->firstWhere('matricula_id', $m->id)?->valor,
                        'peso' => $av->peso,
                    ])->all();
                    $mediasTrim[$t->numero] = $calc->mediaTrimestre($itens);
                    $mediasPorTrimestre[$m->id][$atr->disciplina_id][$t->numero] = $mediasTrim[$t->numero];
                }
                $mediasAnuais[$m->id][$atr->disciplina_id] = $calc->mediaAnual($mediasTrim);
            }
        }

        $mediaGeral = [];
        $situacao = [];
        foreach ($matriculas as $m) {
            $mediaGeral[$m->id] = $calc->mediaGeral($mediasAnuais[$m->id] ?? []);
            $situacao[$m->id] = $calc->situacao($mediasAnuais[$m->id] ?? []);
        }

        return view('pautas.turma-anual', compact(
            'turma', 'atribuicoes', 'trimestres', 'matriculas',
            'mediasAnuais', 'mediasPorTrimestre', 'mediaGeral', 'situacao', 'calc'
        ));
    }

    /** MODO 4 — Situação final da turma */
    public function situacao(Request $request, Turma $turma)
    {
        $pesos = $this->pesosFromRequest($request);
        $calc = new PautaCalculator($pesos);

        $turma->load(['classe', 'curso', 'anoLectivo']);

        $atribuicoes = Atribuicao::with('disciplina')
            ->where('turma_id', $turma->id)
            ->where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->get();

        $trimestres = Trimestre::where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->orderBy('numero')->get();

        $matriculas = $this->matriculasDaTurma($turma->id, $turma->ano_lectivo_id);

        $todasAvaliacoes = Avaliacao::whereIn('atribuicao_id', $atribuicoes->pluck('id'))
            ->with('notas')
            ->get()
            ->groupBy(['atribuicao_id', 'trimestre_id']);

        $resumo = [];
        foreach ($matriculas as $m) {
            $mediasAnuais = [];
            $negativas = [];
            foreach ($atribuicoes as $atr) {
                $mediasTrim = [];
                foreach ($trimestres as $t) {
                    $avs = $todasAvaliacoes[$atr->id][$t->id] ?? collect();
                    $itens = $avs->map(fn ($av) => [
                        'valor' => $av->notas->firstWhere('matricula_id', $m->id)?->valor,
                        'peso' => $av->peso,
                    ])->all();
                    $mediasTrim[$t->numero] = $calc->mediaTrimestre($itens);
                }
                $media = $calc->mediaAnual($mediasTrim);
                $mediasAnuais[$atr->disciplina_id] = $media;
                if ($media !== null && $media < $calc->notaMinima) {
                    $negativas[] = $atr->disciplina->nome;
                }
            }
            $resumo[$m->id] = [
                'media_geral' => $calc->mediaGeral($mediasAnuais),
                'situacao' => $calc->situacao($mediasAnuais),
                'negativas' => $negativas,
            ];
        }

        $agrupado = [
            'aprovado' => [],
            'recurso' => [],
            'reprovado' => [],
            'em_curso' => [],
        ];
        foreach ($matriculas as $m) {
            $agrupado[$resumo[$m->id]['situacao']][] = $m;
        }

        return view('pautas.situacao', compact('turma', 'matriculas', 'resumo', 'agrupado', 'calc'));
    }

    // ----- helpers -----

    protected function matriculasDaTurma(int $turmaId, int $anoId)
    {
        return Matricula::with('aluno.user')
            ->where('turma_id', $turmaId)
            ->where('ano_lectivo_id', $anoId)
            ->whereIn('estado', ['activa', 'aprovado', 'reprovado', 'transferido'])
            ->get()
            ->sortBy(fn ($m) => $m->aluno->user->name);
    }

    protected function pesosFromRequest(Request $request): array
    {
        $defaults = config('escola.pesos_trimestres', [1, 1, 1]);
        return [
            max(1, min(5, (int) $request->query('peso_t1', $defaults[0]))),
            max(1, min(5, (int) $request->query('peso_t2', $defaults[1]))),
            max(1, min(5, (int) $request->query('peso_t3', $defaults[2]))),
        ];
    }
}
