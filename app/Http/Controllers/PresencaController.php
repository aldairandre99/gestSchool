<?php

namespace App\Http\Controllers;

use App\Models\Atribuicao;
use App\Models\Matricula;
use App\Models\Presenca;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $atribuicoes = Atribuicao::query()
            ->with(['turma.classe', 'disciplina', 'anoLectivo'])
            ->when($user->hasAnyRole(['professor', 'professor_assistente']), function ($q) use ($user) {
                if ($prof = $user->professor) {
                    $q->where('professor_id', $prof->id);
                }
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('presencas.index', compact('atribuicoes'));
    }

    public function folha(Request $request, Atribuicao $atribuicao)
    {
        $this->authorizeAtribuicao($request, $atribuicao);
        $data = $request->query('data', now()->toDateString());

        $matriculas = Matricula::with('aluno.user')
            ->where('turma_id', $atribuicao->turma_id)
            ->where('ano_lectivo_id', $atribuicao->ano_lectivo_id)
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->aluno->user->name);

        $existentes = Presenca::where('atribuicao_id', $atribuicao->id)
            ->where('data', $data)
            ->get()
            ->keyBy('matricula_id');

        $atribuicao->load(['turma.classe', 'disciplina']);

        return view('presencas.folha', compact('atribuicao', 'data', 'matriculas', 'existentes'));
    }

    public function gravar(Request $request, Atribuicao $atribuicao)
    {
        $this->authorizeAtribuicao($request, $atribuicao);
        $request->validate([
            'data' => ['required', 'date'],
            'estados' => ['array'],
            'estados.*' => ['in:presente,falta,falta_justificada,atraso'],
        ]);

        $data = $request->input('data');
        $estados = $request->input('estados', []);
        $observacoes = $request->input('observacoes', []);

        foreach ($estados as $matriculaId => $estado) {
            Presenca::updateOrCreate(
                ['atribuicao_id' => $atribuicao->id, 'matricula_id' => $matriculaId, 'data' => $data],
                [
                    'estado' => $estado,
                    'observacao' => $observacoes[$matriculaId] ?? null,
                    'registado_por' => $request->user()->id,
                ]
            );
        }

        return redirect()->route('presencas.folha', ['atribuicao' => $atribuicao->id, 'data' => $data])
            ->with('status', __('Resource updated successfully.'));
    }

    protected function authorizeAtribuicao(Request $request, Atribuicao $atribuicao): void
    {
        $user = $request->user();
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario'])) {
            return;
        }
        $prof = $user->professor;
        abort_unless($prof && $atribuicao->professor_id === $prof->id, 403);
    }
}
