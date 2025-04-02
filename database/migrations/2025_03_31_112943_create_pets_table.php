<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->unsignedBigInteger('codigo')->primary()->autoIncrement();
            $table->string('nome', 100);
            $table->dateTime('data_nasc');
            $table->string('raca', 100);
            $table->index('raca', 'fk_pet_raca_idx');
            $table->foreign('raca', 'fk_pet_raca_idx')
                ->references('nome')
                ->on('racas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
