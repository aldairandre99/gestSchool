<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('turma_id')->constrained('turmas')->cascadeOnDelete();
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->cascadeOnDelete();
            $table->string('numero_matricula', 30)->unique();
            $table->date('data_matricula');
            $table->enum('estado', ['activa', 'transferido', 'desistente', 'aprovado', 'reprovado'])->default('activa');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['aluno_id', 'ano_lectivo_id']);
            $table->index(['turma_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
