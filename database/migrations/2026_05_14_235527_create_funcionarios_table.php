<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('numero_funcionario', 30)->unique()->nullable();
            $table->string('bi', 30)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('cargo', 100)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->date('data_admissao')->nullable();
            $table->text('morada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
