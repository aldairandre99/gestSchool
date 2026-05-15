<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atribuicao_id')->constrained('atribuicoes')->cascadeOnDelete();
            $table->unsignedSmallInteger('dia_semana');     // 1=segunda … 7=domingo
            $table->unsignedSmallInteger('tempo');           // 1..8 conforme config('escola.tempos_lectivos')
            $table->string('sala', 30)->nullable();
            $table->string('observacao', 200)->nullable();
            $table->timestamps();

            $table->unique(['atribuicao_id', 'dia_semana', 'tempo'], 'horarios_atr_dia_tempo_unique');
            $table->index(['dia_semana', 'tempo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
