<?php

namespace App\Http\Controllers;

use App\Models\Atribuicao;
use App\Models\Avaliacao;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AvaliacaoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $avaliacoes = Avaliacao::with(['atribuicao.turma.classe', 'atribuicao.disciplina', 'trimestre'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) {
                    $q->whereHas('atribuicao', fn ($a) => $a->where('professor_id', $prof->id));
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('avaliacoes.index', compact('avaliacoes'));
    }

    public function create()
    {
        return view('avaliacoes.create', $this->options(request()));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Avaliacao::create($data);
        return redirect()->route('avaliacoes.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Avaliacao $avaliacao)
    {
        $avaliacao->load(['atribuicao.turma.classe', 'atribuicao.disciplina', 'trimestre', 'notas.matricula.aluno.user']);
        return view('avaliacoes.show', compact('avaliacao'));
    }

    public function edit(Avaliacao $avaliacao)
    {
        return view('avaliacoes.edit', array_merge(['avaliacao' => $avaliacao], $this->options(request())));
    }

    public function update(Request $request, Avaliacao $avaliacao)
    {
        $avaliacao->update($this->validateData($request, $avaliacao));
        return redirect()->route('avaliacoes.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Avaliacao $avaliacao)
    {
        $avaliacao->delete();
        return redirect()->route('avaliacoes.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(Request $request): array
    {
        $user = $request->user();
        $atribuicoes = Atribuicao::with(['turma.classe', 'disciplina'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) {
                    $q->where('professor_id', $prof->id);
                }
            })->get();

        return [
            'atribuicoes' => $atribuicoes,
            'trimestres' => Trimestre::with('anoLectivo')->orderBy('ano_lectivo_id', 'desc')->orderBy('numero')->get(),
        ];
    }

    protected function validateData(Request $request, ?Avaliacao $a = null): array
    {
        return $request->validate([
            'atribuicao_id' => ['required', Rule::exists('atribuicoes', 'id')],
            'trimestre_id' => ['required', Rule::exists('trimestres', 'id')],
            'tipo' => ['required', Rule::in(['prova', 'teste', 'avaliacao_continua', 'exame'])],
            'titulo' => ['required', 'string', 'max:150'],
            'data' => ['nullable', 'date'],
            'peso' => ['required', 'numeric', 'min:0.1', 'max:10'],
            'max_nota' => ['required', 'numeric', 'min:1', 'max:20'],
        ]);
    }
}
