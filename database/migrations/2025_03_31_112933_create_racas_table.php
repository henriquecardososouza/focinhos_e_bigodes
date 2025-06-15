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
        Schema::create('racas', function (Blueprint $table) {
            $table->string("nome", 100)->primary();
            $table->string("tipo", 100);
            $table->index("tipo", "fk_raca_tipo_pet_idx");
            $table->foreign("tipo", "fk_raca_tipo_pet_idx")
                ->references("nome")
                ->on("tipo_pets")
                ->onUpdate("cascade")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racas');
    }
};
