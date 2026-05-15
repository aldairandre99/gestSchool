<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('classes', 'nivel')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropColumn('nivel');
            });
        }

        Schema::table('classes', function (Blueprint $table) {
            $table->enum('nivel', ['ensino_base', 'ensino_medio'])->default('ensino_base')->after('nome');
        });

        DB::table('classes')->where('ordem', '>=', 10)->update(['nivel' => 'ensino_medio']);
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('nivel');
        });
        Schema::table('classes', function (Blueprint $table) {
            $table->string('nivel', 50)->nullable();
        });
    }
};
