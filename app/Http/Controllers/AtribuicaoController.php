<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Atribuicao;
use App\Models\Disciplina;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AtribuicaoController extends Controller
{
    public function index()
    {
        $atribuicoes = Atribuicao::with(['professor.user', 'turma.classe', 'disciplina', 'anoLectivo'])
            ->orderBy('id', 'desc')->paginate(20);
        return view('atribuicoes.index', compact('atribuicoes'));
    }

    public function create() { return view('atribuicoes.create', $this->options()); }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Atribuicao::create($data);
        return redirect()->route('atribuicoes.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Atribuicao $atribuicao)
    {
        $atribuicao->load(['professor.user', 'turma.classe', 'disciplina', 'anoLectivo']);
        return view('atribuicoes.show', compact('atribuicao'));
    }

    public function edit(Atribuicao $atribuicao)
    {
        return view('atribuicoes.edit', array_merge(['atribuicao' => $atribuicao], $this->options()));
    }

    public function update(Request $request, Atribuicao $atribuicao)
    {
        $atribuicao->update($this->validateData($request, $atribuicao));
        return redirect()->route('atribuicoes.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Atribuicao $atribuicao)
    {
        $atribuicao->delete();
        return redirect()->route('atribuicoes.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(): array
    {
        return [
            'professores' => Professor::with('user')->get(),
            'turmas' => Turma::with(['classe', 'anoLectivo'])->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
            'anos' => AnoLectivo::orderBy('codigo', 'desc')->get(),
        ];
    }

    protected function validateData(Request $request, ?Atribuicao $a = null): array
    {
        return $request->validate([
            'professor_id' => ['required', Rule::exists('professores', 'id')],
            'turma_id' => ['required', Rule::exists('turmas', 'id')],
            'disciplina_id' => ['required', Rule::exists('disciplinas', 'id')],
            'ano_lectivo_id' => ['required', Rule::exists('anos_lectivos', 'id')],
        ]);
    }
}
