<?php

namespace App\Http\Controllers;

use App\Models\Atribuicao;
use App\Models\Horario;
use App\Models\Professor;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $turmas = Turma::with(['classe', 'curso', 'anoLectivo'])
            ->orderBy('classe_id')->orderBy('nome')->get();

        $user = $request->user();
        $professores = Professor::with('user')
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) $q->where('id', $prof->id);
            })
            ->orderBy('id')->get();

        return view('horarios.index', compact('turmas', 'professores'));
    }

    public function turma(Request $request, Turma $turma)
    {
        $turma->load(['classe', 'curso', 'anoLectivo']);

        $horarios = Horario::whereHas('atribuicao', fn ($q) => $q->where('turma_id', $turma->id))
            ->with(['atribuicao.disciplina', 'atribuicao.professor.user'])
            ->get()
            ->groupBy(fn ($h) => $h->dia_semana . '-' . $h->tempo);

        return view('horarios.turma', compact('turma', 'horarios'));
    }

    public function professor(Request $request, Professor $professor)
    {
        $professor->load('user');

        $horarios = Horario::whereHas('atribuicao', fn ($q) => $q->where('professor_id', $professor->id))
            ->with(['atribuicao.turma.classe', 'atribuicao.turma.curso', 'atribuicao.disciplina'])
            ->get()
            ->groupBy(fn ($h) => $h->dia_semana . '-' . $h->tempo);

        return view('horarios.professor', compact('professor', 'horarios'));
    }

    public function create(Request $request)
    {
        return view('horarios.create', $this->options($request));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->checkConflitos($data);
        Horario::create($data);
        return redirect()->route('horarios.index')->with('status', __('Resource created successfully.'));
    }

    public function edit(Request $request, Horario $horario)
    {
        $horario->load('atribuicao');
        return view('horarios.edit', array_merge(['horario' => $horario], $this->options($request)));
    }

    public function update(Request $request, Horario $horario)
    {
        $data = $this->validateData($request, $horario);
        $this->checkConflitos($data, $horario->id);
        $horario->update($data);
        return redirect()->route('horarios.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('horarios.index')->with('status', __('Resource deleted successfully.'));
    }

    /**
     * Grelha de bulk edit do horário de uma turma — preenchimento da semana inteira.
     */
    public function bulkTurma(Request $request, Turma $turma)
    {
        $turma->load(['classe', 'curso', 'anoLectivo']);

        $atribuicoes = Atribuicao::with(['disciplina', 'professor.user'])
            ->where('turma_id', $turma->id)
            ->where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->get()
            ->sortBy('disciplina.nome');

        $horariosActuais = Horario::whereHas('atribuicao', fn ($q) => $q->where('turma_id', $turma->id))
            ->get()
            ->keyBy(fn ($h) => $h->dia_semana . '-' . $h->tempo);

        return view('horarios.bulk-turma', [
            'turma' => $turma,
            'atribuicoes' => $atribuicoes,
            'horariosActuais' => $horariosActuais,
            'tempos' => config('escola.tempos_lectivos'),
            'diasSemana' => Horario::diasSemana(),
            'diasLectivos' => config('escola.dias_lectivos', [1,2,3,4,5]),
        ]);
    }

    public function bulkTurmaStore(Request $request, Turma $turma)
    {
        $slots = $request->input('slots', []);   // [dia][tempo] => ['atribuicao_id' => X, 'sala' => Y]

        $validIds = Atribuicao::where('turma_id', $turma->id)
            ->where('ano_lectivo_id', $turma->ano_lectivo_id)
            ->pluck('id')->all();

        // ---- validação em lote (não escreve nada até confirmar tudo OK) ----
        $aGravar = [];           // linhas a inserir
        $vistosProf = [];        // [dia-tempo] => professor_id (para detectar choque entre slots do próprio submit)
        $vistosTurma = [];       // não há choque dentro da turma porque seleccionamos 1 atribuicao por (dia, tempo)

        foreach ($slots as $dia => $temposArr) {
            $dia = (int) $dia;
            if (! in_array($dia, [1,2,3,4,5,6,7], true)) continue;

            foreach ($temposArr as $tempo => $info) {
                $tempo = (int) $tempo;
                if (! array_key_exists($tempo, config('escola.tempos_lectivos'))) continue;

                $atrId = $info['atribuicao_id'] ?? null;
                if (! $atrId) continue;
                if (! in_array((int) $atrId, $validIds, true)) {
                    return back()->withErrors(['slots' => sprintf(__('Invalid assignment for this class in slot %d/%dº.'), $dia, $tempo)])->withInput();
                }

                $atribuicao = Atribuicao::find($atrId);
                $key = "{$dia}-{$tempo}";

                if (isset($vistosProf[$key]) && $vistosProf[$key] !== $atribuicao->professor_id) {
                    // Não deveria acontecer, pois fixmos 1 célula por slot
                }
                $vistosProf[$key] = $atribuicao->professor_id;

                // Conflito com horários de OUTRAS turmas (mesmo professor)
                $conflitoExterno = Horario::where('dia_semana', $dia)
                    ->where('tempo', $tempo)
                    ->whereHas('atribuicao', function ($q) use ($atribuicao, $turma) {
                        $q->where('professor_id', $atribuicao->professor_id)
                          ->where('turma_id', '!=', $turma->id);
                    })
                    ->with(['atribuicao.turma.classe', 'atribuicao.disciplina'])
                    ->first();

                if ($conflitoExterno) {
                    return back()->withErrors([
                        'slots' => sprintf(
                            __('Schedule conflict: %s already teaches %s in class %s (day %s, period %d).'),
                            $atribuicao->professor->user->name,
                            $conflitoExterno->atribuicao->disciplina->nome,
                            $conflitoExterno->atribuicao->turma->nome_completo,
                            $dia,
                            $tempo
                        ),
                    ])->withInput();
                }

                $aGravar[] = [
                    'atribuicao_id' => $atrId,
                    'dia_semana' => $dia,
                    'tempo' => $tempo,
                    'sala' => $info['sala'] ?: null,
                ];
            }
        }

        // ---- gravação atómica ----
        DB::transaction(function () use ($turma, $aGravar) {
            // Apagar horários antigos desta turma (clean slate)
            Horario::whereHas('atribuicao', fn ($q) => $q->where('turma_id', $turma->id))->delete();

            // Inserir novos
            foreach ($aGravar as $row) {
                Horario::create($row);
            }
        });

        return redirect()->route('horarios.turma', $turma)
            ->with('status', sprintf(__('%d slots saved.'), count($aGravar)));
    }

    public function turmaPdf(Request $request, Turma $turma)
    {
        $turma->load(['classe', 'curso', 'anoLectivo']);
        $horarios = Horario::whereHas('atribuicao', fn ($q) => $q->where('turma_id', $turma->id))
            ->with(['atribuicao.disciplina', 'atribuicao.professor.user'])
            ->get()
            ->groupBy(fn ($h) => $h->dia_semana . '-' . $h->tempo);

        $filename = sprintf('horario-%s-%s.pdf',
            str($turma->classe->nome . $turma->nome)->slug(),
            str($turma->anoLectivo->codigo)->slug()
        );

        return Pdf::loadView('pdf.horarios.turma', [
            'turma' => $turma,
            'horarios' => $horarios,
        ])->setPaper('a4', 'landscape')->download($filename);
    }

    public function professorPdf(Request $request, Professor $professor)
    {
        $professor->load('user');
        $horarios = Horario::whereHas('atribuicao', fn ($q) => $q->where('professor_id', $professor->id))
            ->with(['atribuicao.turma.classe', 'atribuicao.turma.curso', 'atribuicao.disciplina'])
            ->get()
            ->groupBy(fn ($h) => $h->dia_semana . '-' . $h->tempo);

        $filename = sprintf('horario-prof-%s.pdf', str($professor->user->name)->slug());

        return Pdf::loadView('pdf.horarios.professor', [
            'professor' => $professor,
            'horarios' => $horarios,
        ])->setPaper('a4', 'landscape')->download($filename);
    }

    // ----- helpers -----

    protected function options(Request $request): array
    {
        $user = $request->user();
        $atribuicoes = Atribuicao::with(['turma.classe', 'turma.curso', 'disciplina', 'professor.user', 'anoLectivo'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) $q->where('professor_id', $prof->id);
            })
            ->get();

        return [
            'atribuicoes' => $atribuicoes,
            'tempos' => config('escola.tempos_lectivos'),
            'diasSemana' => Horario::diasSemana(),
        ];
    }

    protected function validateData(Request $request, ?Horario $horario = null): array
    {
        return $request->validate([
            'atribuicao_id' => ['required', Rule::exists('atribuicoes', 'id')],
            'dia_semana' => ['required', 'integer', 'between:1,7'],
            'tempo' => ['required', 'integer', Rule::in(array_keys(config('escola.tempos_lectivos')))],
            'sala' => ['nullable', 'string', 'max:30'],
            'observacao' => ['nullable', 'string', 'max:200'],
        ]);
    }

    protected function checkConflitos(array $data, ?int $excludeId = null): void
    {
        $atribuicao = Atribuicao::with('professor', 'turma')->find($data['atribuicao_id']);
        if (! $atribuicao) return;

        // Professor já tem aula nesse slot?
        $conflitoProf = Horario::where('dia_semana', $data['dia_semana'])
            ->where('tempo', $data['tempo'])
            ->whereHas('atribuicao', fn ($q) => $q->where('professor_id', $atribuicao->professor_id))
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->with('atribuicao.disciplina', 'atribuicao.turma.classe')
            ->first();

        if ($conflitoProf) {
            throw ValidationException::withMessages([
                'atribuicao_id' => sprintf(
                    __('Teacher already has a lesson in this slot: %s in class %s.'),
                    $conflitoProf->atribuicao->disciplina->nome,
                    $conflitoProf->atribuicao->turma->nome_completo
                ),
            ]);
        }

        // Turma já tem aula nesse slot?
        $conflitoTurma = Horario::where('dia_semana', $data['dia_semana'])
            ->where('tempo', $data['tempo'])
            ->whereHas('atribuicao', fn ($q) => $q->where('turma_id', $atribuicao->turma_id))
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->with('atribuicao.disciplina')
            ->first();

        if ($conflitoTurma) {
            throw ValidationException::withMessages([
                'atribuicao_id' => sprintf(
                    __('Class already has a lesson in this slot: %s.'),
                    $conflitoTurma->atribuicao->disciplina->nome
                ),
            ]);
        }
    }
}
