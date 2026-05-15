<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->cascadeOnDelete();
            $table->unsignedSmallInteger('numero');
            $table->date('inicio');
            $table->date('fim');
            $table->boolean('aberto')->default(true);
            $table->timestamps();

            $table->unique(['ano_lectivo_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trimestres');
    }
};
