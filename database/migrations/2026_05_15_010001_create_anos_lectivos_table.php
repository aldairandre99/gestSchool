<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anos_lectivos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 9)->unique();
            $table->date('inicio');
            $table->date('fim');
            $table->boolean('activo')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anos_lectivos');
    }
};
