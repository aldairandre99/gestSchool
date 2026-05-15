<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::orderBy('ordem')->paginate(15);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $disciplinas = Disciplina::orderBy('nome')->get();
        return view('classes.create', compact('disciplinas'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $classe = Classe::create($data);
        $classe->disciplinas()->sync($data['disciplinas'] ?? []);
        return redirect()->route('classes.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Classe $classe)
    {
        $classe->load(['disciplinas', 'turmas.anoLectivo']);
        return view('classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        $classe->load('disciplinas');
        $disciplinas = Disciplina::orderBy('nome')->get();
        return view('classes.edit', compact('classe', 'disciplinas'));
    }

    public function update(Request $request, Classe $classe)
    {
        $data = $this->validateData($request, $classe);
        $classe->update($data);
        $classe->disciplinas()->sync($data['disciplinas'] ?? []);
        return redirect()->route('classes.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('classes.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function validateData(Request $request, ?Classe $classe = null): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:50'],
            'ordem' => ['nullable', 'integer', 'min:0'],
            'nivel' => ['nullable', 'string', 'max:50'],
            'disciplinas' => ['array'],
            'disciplinas.*' => ['integer', Rule::exists('disciplinas', 'id')],
        ]);
    }
}
