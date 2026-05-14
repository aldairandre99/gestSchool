<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aluno_encarregado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('encarregado_id')->constrained('encarregados')->cascadeOnDelete();
            $table->enum('parentesco', ['pai', 'mae', 'tutor', 'irmao', 'outro'])->default('outro');
            $table->boolean('principal')->default(false);
            $table->timestamps();

            $table->unique(['aluno_id', 'encarregado_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aluno_encarregado');
    }
};
