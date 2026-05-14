<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('numero_processo', 30)->unique();
            $table->string('bi', 30)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('classe', 30)->nullable();
            $table->string('turma', 30)->nullable();
            $table->string('ano_lectivo', 9)->nullable();
            $table->string('nacionalidade', 50)->default('Angolana');
            $table->string('naturalidade', 100)->nullable();
            $table->text('morada')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
