<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atribuicao_id')->constrained('atribuicoes')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->date('data');
            $table->enum('estado', ['presente', 'falta', 'falta_justificada', 'atraso'])->default('presente');
            $table->string('observacao', 255)->nullable();
            $table->foreignId('registado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['atribuicao_id', 'matricula_id', 'data']);
            $table->index(['data', 'atribuicao_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presencas');
    }
};
