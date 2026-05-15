<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacoes')->cascadeOnDelete();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->decimal('valor', 5, 2)->nullable();
            $table->string('observacao', 255)->nullable();
            $table->timestamps();

            $table->unique(['avaliacao_id', 'matricula_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
