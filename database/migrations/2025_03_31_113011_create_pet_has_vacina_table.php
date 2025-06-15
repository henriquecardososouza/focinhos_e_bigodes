<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pet_has_vacina', function (Blueprint $table) {
            $table->unsignedBigInteger('pet');
            $table->index('pet', 'fk_pet_has_vacina_pets_idx');
            $table->foreign('pet', 'fk_pet_has_vacina_pets_idx')
                ->references('codigo')
                ->on('pets')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('vacina', 100);
            $table->index('vacina', 'fk_pet_has_vacina_vacinas_idx');
            $table->foreign('vacina', 'fk_pet_has_vacina_vacinas_idx')
                ->references('nome')
                ->on('vacinas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dateTime("data");
            $table->unsignedInteger("dose");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_has_vacina');
    }
};
