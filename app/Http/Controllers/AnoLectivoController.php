<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnoLectivoController extends Controller
{
    public function index()
    {
        $anos = AnoLectivo::orderBy('codigo', 'desc')->paginate(15);
        return view('anos.index', compact('anos'));
    }

    public function create() { return view('anos.create'); }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if (! empty($data['activo'])) AnoLectivo::query()->update(['activo' => false]);
        AnoLectivo::create($data);
        return redirect()->route('anos.index')->with('status', __('Resource created successfully.'));
    }

    public function show(AnoLectivo $ano) { return view('anos.show', compact('ano')); }

    public function edit(AnoLectivo $ano) { return view('anos.edit', compact('ano')); }

    public function update(Request $request, AnoLectivo $ano)
    {
        $data = $this->validateData($request, $ano);
        if (! empty($data['activo'])) AnoLectivo::where('id', '!=', $ano->id)->update(['activo' => false]);
        $ano->update($data);
        return redirect()->route('anos.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(AnoLectivo $ano)
    {
        $ano->delete();
        return redirect()->route('anos.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function validateData(Request $request, ?AnoLectivo $ano = null): array
    {
        return $request->validate([
            'codigo' => ['required', 'string', 'max:9', Rule::unique('anos_lectivos', 'codigo')->ignore($ano?->id)],
            'inicio' => ['required', 'date'],
            'fim' => ['required', 'date', 'after:inicio'],
            'activo' => ['nullable', 'boolean'],
        ]);
    }
}
