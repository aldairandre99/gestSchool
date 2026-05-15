<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atribuicao_id')->constrained('atribuicoes')->cascadeOnDelete();
            $table->date('data');
            $table->unsignedSmallInteger('numero')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->text('sumario')->nullable();
            $table->text('conteudo_planeado')->nullable();
            $table->foreignId('registado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['atribuicao_id', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
