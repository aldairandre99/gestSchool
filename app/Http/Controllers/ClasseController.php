<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Curriculo;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::orderBy('ordem')->paginate(20);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create', $this->options());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $classe = Classe::create($data);
        if ($classe->nivel === 'ensino_base') {
            $this->syncDisciplinasBase($classe, $request->input('disciplinas', []));
        }
        return redirect()->route('classes.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Classe $classe)
    {
        $classe->load(['disciplinas', 'turmas.anoLectivo', 'cursos']);
        return view('classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        $classe->load('disciplinas');
        return view('classes.edit', array_merge(['classe' => $classe], $this->options()));
    }

    public function update(Request $request, Classe $classe)
    {
        $data = $this->validateData($request, $classe);
        $classe->update($data);
        if ($classe->nivel === 'ensino_base') {
            $this->syncDisciplinasBase($classe, $request->input('disciplinas', []));
        }
        return redirect()->route('classes.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(): array
    {
        return ['disciplinas' => Disciplina::orderBy('nome')->get()];
    }

    protected function syncDisciplinasBase(Classe $classe, array $disciplinaIds): void
    {
        // Remove só as do ensino base (curso_id NULL), mantendo as do médio
        Curriculo::where('classe_id', $classe->id)->whereNull('curso_id')->delete();
        foreach ($disciplinaIds as $id) {
            Curriculo::create([
                'classe_id' => $classe->id,
                'curso_id' => null,
                'disciplina_id' => $id,
            ]);
        }
    }

    protected function validateData(Request $request, ?Classe $classe = null): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:50'],
            'nivel' => ['required', Rule::in(['ensino_base', 'ensino_medio'])],
            'ordem' => ['nullable', 'integer', 'min:0'],
            'disciplinas' => ['array'],
            'disciplinas.*' => ['integer', Rule::exists('disciplinas', 'id')],
        ]);
    }
}
