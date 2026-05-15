<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AnoLectivoController;
use App\Http\Controllers\AtribuicaoController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\BoletimController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\EncarregadoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\PautaController;
use App\Http\Controllers\PresencaController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrimestreController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:director_geral|director_pedagogico|secretario')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('funcionarios', FuncionarioController::class)->parameters(['funcionarios' => 'funcionario']);
        Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'professor']);
        Route::resource('alunos', AlunoController::class)->parameters(['alunos' => 'aluno']);
        Route::resource('encarregados', EncarregadoController::class)->parameters(['encarregados' => 'encarregado']);

        Route::resource('anos', AnoLectivoController::class)->parameters(['anos' => 'ano']);
        Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
        Route::resource('turmas', TurmaController::class)->parameters(['turmas' => 'turma']);
        Route::resource('disciplinas', DisciplinaController::class)->parameters(['disciplinas' => 'disciplina']);
        Route::resource('matriculas', MatriculaController::class)->parameters(['matriculas' => 'matricula']);
        Route::resource('atribuicoes', AtribuicaoController::class)->parameters(['atribuicoes' => 'atribuicao']);
        Route::resource('trimestres', TrimestreController::class)->parameters(['trimestres' => 'trimestre']);
    });

    // Acesso para direcção + professores
    Route::middleware('role:director_geral|director_pedagogico|secretario|professor|professor_assistente')->group(function () {
        Route::resource('aulas', AulaController::class)->parameters(['aulas' => 'aula']);
        Route::get('/aulas/{aula}/presencas', [PresencaController::class, 'folha'])->name('presencas.folha');
        Route::post('/aulas/{aula}/presencas', [PresencaController::class, 'gravar'])->name('presencas.gravar');
        Route::get('/presencas', [PresencaController::class, 'index'])->name('presencas.index');

        Route::resource('avaliacoes', AvaliacaoController::class)->parameters(['avaliacoes' => 'avaliacao']);

        Route::get('/notas/{avaliacao}/folha', [NotaController::class, 'folha'])->name('notas.folha');
        Route::post('/notas/{avaliacao}/gravar', [NotaController::class, 'gravar'])->name('notas.gravar');

        Route::get('/pautas', [PautaController::class, 'index'])->name('pautas.index');
        Route::get('/pautas/{atribuicao}/{trimestre}', [PautaController::class, 'show'])->name('pautas.show');
    });

    // Boletim acessível a direcção, professores e encarregado do aluno
    Route::get('/boletim/{matricula}', [BoletimController::class, 'show'])->name('boletim.show');

    Route::resource('comunicados', ComunicadoController::class)->parameters(['comunicados' => 'comunicado']);

    Route::middleware('role:professor|professor_assistente')->group(function () {
        Route::get('/meus-alunos', [AlunoController::class, 'index'])->name('meus-alunos.index');
    });

    Route::middleware('role:encarregado')->group(function () {
        Route::get('/meus-educandos', function () {
            $encarregado = auth()->user()->encarregado()->with('alunos.user')->first();
            return view('encarregado.meus-educandos', [
                'alunos' => $encarregado?->alunos ?? collect(),
            ]);
        })->name('meus-educandos.index');

        Route::get('/meus-educandos/{aluno}', function (\App\Models\Aluno $aluno) {
            $encarregado = auth()->user()->encarregado;
            abort_unless($encarregado && $encarregado->alunos()->whereKey($aluno->id)->exists(), 403);
            $aluno->load(['user', 'encarregados.user', 'matriculas.turma.classe', 'matriculas.anoLectivo']);
            return view('encarregado.aluno-perfil', compact('aluno'));
        })->name('meus-educandos.show');
    });
});

require __DIR__.'/auth.php';
