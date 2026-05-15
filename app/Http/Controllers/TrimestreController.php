<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrimestreController extends Controller
{
    public function index()
    {
        $trimestres = Trimestre::with('anoLectivo')->orderBy('ano_lectivo_id', 'desc')->orderBy('numero')->paginate(30);
        return view('trimestres.index', compact('trimestres'));
    }

    public function create()
    {
        return view('trimestres.create', ['anos' => AnoLectivo::orderBy('codigo', 'desc')->get()]);
    }

    public function store(Request $request)
    {
        Trimestre::create($this->validateData($request));
        return redirect()->route('trimestres.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Trimestre $trimestre)
    {
        $trimestre->load('anoLectivo');
        return view('trimestres.show', compact('trimestre'));
    }

    public function edit(Trimestre $trimestre)
    {
        return view('trimestres.edit', [
            'trimestre' => $trimestre,
            'anos' => AnoLectivo::orderBy('codigo', 'desc')->get(),
        ]);
    }

    public function update(Request $request, Trimestre $trimestre)
    {
        $trimestre->update($this->validateData($request, $trimestre));
        return redirect()->route('trimestres.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Trimestre $trimestre)
    {
        $trimestre->delete();
        return redirect()->route('trimestres.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function validateData(Request $request, ?Trimestre $t = null): array
    {
        return $request->validate([
            'ano_lectivo_id' => ['required', Rule::exists('anos_lectivos', 'id')],
            'numero' => ['required', 'integer', 'in:1,2,3'],
            'inicio' => ['required', 'date'],
            'fim' => ['required', 'date', 'after:inicio'],
            'aberto' => ['nullable', 'boolean'],
        ]);
    }
}
