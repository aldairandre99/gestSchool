<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            $table->foreignId('curso_id')->nullable()->after('classe_id')->constrained('cursos')->nullOnDelete();
            $table->index(['curso_id', 'ano_lectivo_id']);
        });
    }

    public function down(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            $table->dropForeign(['curso_id']);
            $table->dropIndex(['curso_id', 'ano_lectivo_id']);
            $table->dropColumn('curso_id');
        });
    }
};
