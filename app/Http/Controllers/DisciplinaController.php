<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DisciplinaController extends Controller
{
    public function index()
    {
        $disciplinas = Disciplina::orderBy('nome')->paginate(20);
        return view('disciplinas.index', compact('disciplinas'));
    }

    public function create() { return view('disciplinas.create'); }

    public function store(Request $request)
    {
        Disciplina::create($this->validateData($request));
        return redirect()->route('disciplinas.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Disciplina $disciplina)
    {
        $disciplina->load('classes');
        return view('disciplinas.show', compact('disciplina'));
    }

    public function edit(Disciplina $disciplina) { return view('disciplinas.edit', compact('disciplina')); }

    public function update(Request $request, Disciplina $disciplina)
    {
        $disciplina->update($this->validateData($request, $disciplina));
        return redirect()->route('disciplinas.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Disciplina $disciplina)
    {
        $disciplina->delete();
        return redirect()->route('disciplinas.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function validateData(Request $request, ?Disciplina $disciplina = null): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:100', Rule::unique('disciplinas', 'nome')->ignore($disciplina?->id)],
            'sigla' => ['nullable', 'string', 'max:10'],
            'carga_horaria_semanal' => ['nullable', 'integer', 'min:0', 'max:40'],
            'activa' => ['nullable', 'boolean'],
        ]);
    }
}
