<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::withCount('classes')->orderBy('nome')->paginate(20);
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('cursos.create', $this->options());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $curso = Curso::create($data);
        $this->syncClasses($curso, $request);
        return redirect()->route('cursos.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Curso $curso)
    {
        $curso->load('classes', 'turmas.classe');
        return view('cursos.show', compact('curso'));
    }

    public function edit(Curso $curso)
    {
        $curso->load('classes');
        return view('cursos.edit', array_merge(['curso' => $curso], $this->options()));
    }

    public function update(Request $request, Curso $curso)
    {
        $curso->update($this->validateData($request, $curso));
        $this->syncClasses($curso, $request);
        return redirect()->route('cursos.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(): array
    {
        return ['classesMedio' => Classe::where('nivel', 'ensino_medio')->orderBy('ordem')->get()];
    }

    protected function validateData(Request $request, ?Curso $curso = null): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'sigla' => ['required', 'string', 'max:10', Rule::unique('cursos', 'sigla')->ignore($curso?->id)],
            'descricao' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);
    }

    protected function syncClasses(Curso $curso, Request $request): void
    {
        $sync = [];
        foreach ($request->input('classes', []) as $classeId => $ano) {
            if ($ano === null || $ano === '') continue;
            $sync[$classeId] = ['ano' => (int) $ano];
        }
        $curso->classes()->sync($sync);
    }
}
