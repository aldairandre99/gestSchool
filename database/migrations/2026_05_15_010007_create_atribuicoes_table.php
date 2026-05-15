<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atribuicoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professor_id')->constrained('professores')->cascadeOnDelete();
            $table->foreignId('turma_id')->constrained('turmas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['professor_id', 'turma_id', 'disciplina_id', 'ano_lectivo_id'], 'atribuicoes_unique');
            $table->index(['turma_id', 'disciplina_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atribuicoes');
    }
};
