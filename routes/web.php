<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EncarregadoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');

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
    });

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
            $aluno->load(['user', 'encarregados.user']);
            return view('encarregado.aluno-perfil', compact('aluno'));
        })->name('meus-educandos.show');
    });
});

require __DIR__.'/auth.php';
