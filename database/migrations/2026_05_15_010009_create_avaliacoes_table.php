<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atribuicao_id')->constrained('atribuicoes')->cascadeOnDelete();
            $table->foreignId('trimestre_id')->constrained('trimestres')->cascadeOnDelete();
            $table->enum('tipo', ['prova', 'teste', 'avaliacao_continua', 'exame'])->default('teste');
            $table->string('titulo', 150);
            $table->date('data')->nullable();
            $table->decimal('peso', 4, 2)->default(1.00);
            $table->decimal('max_nota', 4, 2)->default(20.00);
            $table->timestamps();

            $table->index(['atribuicao_id', 'trimestre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
