<?php

namespace App\Http\Controllers;

use App\Models\Atribuicao;
use App\Models\Horario;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Http\Request;
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
                    'O professor já lecciona %s na turma %s neste dia/tempo.',
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
                    'A turma já tem %s neste dia/tempo.',
                    $conflitoTurma->atribuicao->disciplina->nome
                ),
            ]);
        }
    }
}
