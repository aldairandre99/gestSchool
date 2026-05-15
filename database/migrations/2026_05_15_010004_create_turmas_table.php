<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->cascadeOnDelete();
            $table->string('nome', 30);
            $table->string('sala', 30)->nullable();
            $table->string('turno', 20)->nullable();
            $table->unsignedSmallInteger('capacidade')->default(40);
            $table->foreignId('director_turma_id')->nullable()->constrained('professores')->nullOnDelete();
            $table->timestamps();

            $table->unique(['classe_id', 'ano_lectivo_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
