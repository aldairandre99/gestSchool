<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presencas', function (Blueprint $table) {
            $table->foreignId('aula_id')->nullable()->after('id')->constrained('aulas')->cascadeOnDelete();
        });

        $pares = DB::table('presencas')
            ->select('atribuicao_id', 'data')
            ->distinct()
            ->get();

        foreach ($pares as $par) {
            $aulaId = DB::table('aulas')->insertGetId([
                'atribuicao_id' => $par->atribuicao_id,
                'data' => $par->data,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('presencas')
                ->where('atribuicao_id', $par->atribuicao_id)
                ->where('data', $par->data)
                ->update(['aula_id' => $aulaId]);
        }

        Schema::table('presencas', function (Blueprint $table) {
            $table->dropUnique(['atribuicao_id', 'matricula_id', 'data']);
            $table->dropIndex(['data', 'atribuicao_id']);
            $table->dropForeign(['atribuicao_id']);
            $table->dropColumn(['atribuicao_id', 'data']);
        });

        Schema::table('presencas', function (Blueprint $table) {
            $table->foreignId('aula_id')->nullable(false)->change();
            $table->unique(['aula_id', 'matricula_id']);
        });
    }

    public function down(): void
    {
        Schema::table('presencas', function (Blueprint $table) {
            $table->foreignId('atribuicao_id')->nullable()->constrained('atribuicoes')->cascadeOnDelete();
            $table->date('data')->nullable();
        });

        $linhas = DB::table('presencas')
            ->join('aulas', 'aulas.id', '=', 'presencas.aula_id')
            ->select('presencas.id', 'aulas.atribuicao_id', 'aulas.data')
            ->get();
        foreach ($linhas as $linha) {
            DB::table('presencas')->where('id', $linha->id)->update([
                'atribuicao_id' => $linha->atribuicao_id,
                'data' => $linha->data,
            ]);
        }

        Schema::table('presencas', function (Blueprint $table) {
            $table->dropUnique(['aula_id', 'matricula_id']);
            $table->dropForeign(['aula_id']);
            $table->dropColumn('aula_id');
            $table->foreignId('atribuicao_id')->nullable(false)->change();
            $table->date('data')->nullable(false)->change();
            $table->unique(['atribuicao_id', 'matricula_id', 'data']);
            $table->index(['data', 'atribuicao_id']);
        });
    }
};
