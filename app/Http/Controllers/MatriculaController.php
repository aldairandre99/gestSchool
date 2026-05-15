<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AnoLectivo;
use App\Models\Matricula;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $anoId = $request->query('ano_lectivo_id');
        $turmaId = $request->query('turma_id');

        $matriculas = Matricula::with(['aluno.user', 'turma.classe', 'anoLectivo'])
            ->when($anoId, fn ($qb) => $qb->where('ano_lectivo_id', $anoId))
            ->when($turmaId, fn ($qb) => $qb->where('turma_id', $turmaId))
            ->when($q, fn ($qb) => $qb->where(function ($w) use ($q) {
                $w->where('numero_matricula', 'ilike', "%$q%")
                  ->orWhereHas('aluno.user', fn ($u) => $u->where('name', 'ilike', "%$q%"));
            }))
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('matriculas.index', [
            'matriculas' => $matriculas, 'q' => $q,
            'anos' => AnoLectivo::orderBy('codigo', 'desc')->get(),
            'turmas' => Turma::with('classe')->get(),
            'anoId' => $anoId, 'turmaId' => $turmaId,
        ]);
    }

    public function create()
    {
        return view('matriculas.create', $this->options());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Matricula::create($data);
        return redirect()->route('matriculas.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Matricula $matricula)
    {
        $matricula->load(['aluno.user', 'turma.classe', 'anoLectivo']);
        return view('matriculas.show', compact('matricula'));
    }

    public function edit(Matricula $matricula)
    {
        return view('matriculas.edit', array_merge(['matricula' => $matricula], $this->options()));
    }

    public function update(Request $request, Matricula $matricula)
    {
        $data = $this->validateData($request, $matricula);
        $matricula->update($data);
        return redirect()->route('matriculas.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();
        return redirect()->route('matriculas.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(): array
    {
        return [
            'alunos' => Aluno::with('user')->get(),
            'turmas' => Turma::with(['classe', 'anoLectivo'])->get(),
            'anos' => AnoLectivo::orderBy('codigo', 'desc')->get(),
        ];
    }

    protected function validateData(Request $request, ?Matricula $matricula = null): array
    {
        $id = $matricula?->id;
        return $request->validate([
            'aluno_id' => ['required', Rule::exists('alunos', 'id')],
            'turma_id' => ['required', Rule::exists('turmas', 'id')],
            'ano_lectivo_id' => ['required', Rule::exists('anos_lectivos', 'id')],
            'numero_matricula' => ['required', 'string', 'max:30', Rule::unique('matriculas', 'numero_matricula')->ignore($id)],
            'data_matricula' => ['required', 'date'],
            'estado' => ['required', Rule::in(['activa', 'transferido', 'desistente', 'aprovado', 'reprovado'])],
            'observacoes' => ['nullable', 'string'],
        ]);
    }
}
