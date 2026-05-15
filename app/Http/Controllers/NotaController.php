<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Matricula;
use App\Models\Nota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function folha(Request $request, Avaliacao $avaliacao)
    {
        $this->authorizeAvaliacao($request, $avaliacao);
        $avaliacao->load(['atribuicao.turma.classe', 'atribuicao.disciplina', 'trimestre']);

        $matriculas = Matricula::with('aluno.user')
            ->where('turma_id', $avaliacao->atribuicao->turma_id)
            ->where('ano_lectivo_id', $avaliacao->atribuicao->ano_lectivo_id)
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->aluno->user->name);

        $existentes = Nota::where('avaliacao_id', $avaliacao->id)->get()->keyBy('matricula_id');

        return view('notas.folha', compact('avaliacao', 'matriculas', 'existentes'));
    }

    public function gravar(Request $request, Avaliacao $avaliacao)
    {
        $this->authorizeAvaliacao($request, $avaliacao);
        $request->validate([
            'valores' => ['array'],
            'valores.*' => ['nullable', 'numeric', 'min:0', 'max:' . $avaliacao->max_nota],
        ]);

        $valores = $request->input('valores', []);
        $observacoes = $request->input('observacoes', []);

        foreach ($valores as $matriculaId => $valor) {
            if ($valor === null || $valor === '') {
                Nota::where('avaliacao_id', $avaliacao->id)->where('matricula_id', $matriculaId)->delete();
                continue;
            }
            Nota::updateOrCreate(
                ['avaliacao_id' => $avaliacao->id, 'matricula_id' => $matriculaId],
                ['valor' => $valor, 'observacao' => $observacoes[$matriculaId] ?? null]
            );
        }

        return redirect()->route('notas.folha', $avaliacao)->with('status', __('Resource updated successfully.'));
    }

    protected function authorizeAvaliacao(Request $request, Avaliacao $avaliacao): void
    {
        $user = $request->user();
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario'])) return;
        $prof = $user->professor;
        abort_unless($prof && $avaliacao->atribuicao->professor_id === $prof->id, 403);
    }
}
