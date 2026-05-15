<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comunicados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->longText('conteudo');
            $table->foreignId('autor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('alcance', ['todos', 'professores', 'encarregados', 'classe', 'turma'])->default('todos');
            $table->foreignId('classe_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('turma_id')->nullable()->constrained('turmas')->nullOnDelete();
            $table->timestamp('publicado_em')->nullable();
            $table->timestamps();

            $table->index(['alcance', 'publicado_em']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunicados');
    }
};
