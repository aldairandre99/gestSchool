<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Matricula;
use App\Models\Presenca;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('aulas.index');
    }

    public function folha(Request $request, Aula $aula)
    {
        $this->authorizeAula($request, $aula);
        $aula->load(['atribuicao.turma.classe', 'atribuicao.disciplina']);

        $matriculas = Matricula::with('aluno.user')
            ->where('turma_id', $aula->atribuicao->turma_id)
            ->where('ano_lectivo_id', $aula->atribuicao->ano_lectivo_id)
            ->where('estado', 'activa')
            ->get()
            ->sortBy(fn ($m) => $m->aluno->user->name);

        $existentes = Presenca::where('aula_id', $aula->id)
            ->get()
            ->keyBy('matricula_id');

        return view('presencas.folha', compact('aula', 'matriculas', 'existentes'));
    }

    public function gravar(Request $request, Aula $aula)
    {
        $this->authorizeAula($request, $aula);
        $request->validate([
            'estados' => ['array'],
            'estados.*' => ['in:presente,falta,falta_justificada,atraso'],
        ]);

        $estados = $request->input('estados', []);
        $observacoes = $request->input('observacoes', []);

        foreach ($estados as $matriculaId => $estado) {
            Presenca::updateOrCreate(
                ['aula_id' => $aula->id, 'matricula_id' => $matriculaId],
                [
                    'estado' => $estado,
                    'observacao' => $observacoes[$matriculaId] ?? null,
                    'registado_por' => $request->user()->id,
                ]
            );
        }

        return redirect()->route('aulas.show', $aula)
            ->with('status', __('Resource updated successfully.'));
    }

    protected function authorizeAula(Request $request, Aula $aula): void
    {
        $user = $request->user();
        if ($user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario'])) return;
        $prof = $user->professor;
        abort_unless($prof && $aula->atribuicao->professor_id === $prof->id, 403);
    }
}
