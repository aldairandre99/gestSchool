<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('classe_disciplina', 'curriculo');

        Schema::table('curriculo', function (Blueprint $table) {
            $table->dropUnique('classe_disciplina_classe_id_disciplina_id_unique');
            $table->foreignId('curso_id')->nullable()->after('classe_id')->constrained('cursos')->cascadeOnDelete();
            $table->index(['classe_id', 'curso_id']);
            $table->unique(['classe_id', 'curso_id', 'disciplina_id'], 'curriculo_unique');
        });
    }

    public function down(): void
    {
        Schema::table('curriculo', function (Blueprint $table) {
            $table->dropUnique('curriculo_unique');
            $table->dropIndex(['classe_id', 'curso_id']);
            $table->dropForeign(['curso_id']);
            $table->dropColumn('curso_id');
            $table->unique(['classe_id', 'disciplina_id']);
        });

        Schema::rename('curriculo', 'classe_disciplina');
    }
};
