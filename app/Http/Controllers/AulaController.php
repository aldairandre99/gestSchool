<?php

namespace App\Http\Controllers;

use App\Models\Atribuicao;
use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AulaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $aulas = Aula::with(['atribuicao.turma.classe', 'atribuicao.disciplina'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) {
                    $q->whereHas('atribuicao', fn ($a) => $a->where('professor_id', $prof->id));
                }
            })
            ->orderBy('data', 'desc')
            ->orderBy('hora_inicio')
            ->paginate(20);

        return view('aulas.index', compact('aulas'));
    }

    public function create(Request $request)
    {
        $atribuicaoId = $request->query('atribuicao_id');
        return view('aulas.create', array_merge(
            $this->options($request),
            ['atribuicaoId' => $atribuicaoId]
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->authorizeAtribuicao($request, Atribuicao::findOrFail($data['atribuicao_id']));

        $data['registado_por'] = $request->user()->id;
        $aula = Aula::create($data);

        return redirect()->route('aulas.show', $aula)->with('status', __('Resource created successfully.'));
    }

    public function show(Aula $aula)
    {
        $aula->load(['atribuicao.turma.classe', 'atribuicao.disciplina', 'presencas.matricula.aluno.user']);
        return view('aulas.show', compact('aula'));
    }

    public function edit(Request $request, Aula $aula)
    {
        $this->authorizeAtribuicao($request, $aula->atribuicao);
        $aula->load('atribuicao.turma.classe', 'atribuicao.disciplina');
        return view('aulas.edit', array_merge(['aula' => $aula], $this->options($request)));
    }

    public function update(Request $request, Aula $aula)
    {
        $this->authorizeAtribuicao($request, $aula->atribuicao);
        $aula->update($this->validateData($request, $aula));
        return redirect()->route('aulas.show', $aula)->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Request $request, Aula $aula)
    {
        $this->authorizeAtribuicao($request, $aula->atribuicao);
        $aula->delete();
        return redirect()->route('aulas.index')->with('status', __('Resource deleted successfully.'));
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
        return compact('atribuicoes');
    }

    protected function validateData(Request $request, ?Aula $aula = null): array
    {
        return $request->validate([
            'atribuicao_id' => ['required', Rule::exists('atribuicoes', 'id')],
            'data' => ['required', 'date'],
            'numero' => ['nullable', 'integer', 'min:1', 'max:300'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fim' => ['nullable', 'date_format:H:i', 'after:hora_inicio'],
            'sumario' => ['nullable', 'string'],
            'conteudo_planeado' => ['nullable', 'string'],
        ]);
    }

    protected function authorizeAtribuicao(Request $request, Atribuicao $atribuicao): void
    {
        $user = $request->user();
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario'])) return;
        $prof = $user->professor;
        abort_unless($prof && $atribuicao->professor_id === $prof->id, 403);
    }
}
