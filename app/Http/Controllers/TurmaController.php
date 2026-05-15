<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $turmas = Turma::with(['classe', 'anoLectivo', 'directorTurma.user'])
            ->withCount(['matriculas as alunos_count' => fn ($q) => $q->where('estado', 'activa')])
            ->orderBy('classe_id')->orderBy('nome')
            ->paginate(20);
        return view('turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('turmas.create', $this->options());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Turma::create($data);
        return redirect()->route('turmas.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Turma $turma)
    {
        $turma->load([
            'classe.disciplinas',
            'anoLectivo',
            'directorTurma.user',
            'matriculas.aluno.user',
            'atribuicoes.disciplina',
            'atribuicoes.professor.user',
        ]);
        return view('turmas.show', compact('turma'));
    }

    public function edit(Turma $turma)
    {
        return view('turmas.edit', array_merge(['turma' => $turma], $this->options()));
    }

    public function update(Request $request, Turma $turma)
    {
        $data = $this->validateData($request, $turma);
        $turma->update($data);
        return redirect()->route('turmas.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();
        return redirect()->route('turmas.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(): array
    {
        return [
            'classes' => Classe::orderBy('ordem')->get(),
            'anos' => AnoLectivo::orderBy('codigo', 'desc')->get(),
            'professores' => Professor::with('user')->get(),
        ];
    }

    protected function validateData(Request $request, ?Turma $turma = null): array
    {
        return $request->validate([
            'classe_id' => ['required', Rule::exists('classes', 'id')],
            'ano_lectivo_id' => ['required', Rule::exists('anos_lectivos', 'id')],
            'nome' => ['required', 'string', 'max:30'],
            'sala' => ['nullable', 'string', 'max:30'],
            'turno' => ['nullable', 'string', 'max:20'],
            'capacidade' => ['nullable', 'integer', 'min:1', 'max:200'],
            'director_turma_id' => ['nullable', Rule::exists('professores', 'id')],
        ]);
    }
}
