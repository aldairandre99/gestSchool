<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_escolares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->cascadeOnDelete();
            $table->string('titulo', 200);
            $table->text('descricao')->nullable();
            $table->string('tipo', 30);              // feriado, ferias, exame, prova, reuniao, evento
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();    // null = evento de um único dia
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->boolean('dia_inteiro')->default(true);
            $table->string('cor', 7)->nullable();    // override da cor do tipo (#rrggbb)
            $table->foreignId('classe_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('turma_id')->nullable()->constrained('turmas')->nullOnDelete();
            $table->foreignId('criado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['ano_lectivo_id', 'data_inicio']);
            $table->index(['tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_escolares');
    }
};
