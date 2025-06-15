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
        Schema::create('cliente_has_pet', function (Blueprint $table) {
            $table->string("cliente");
            $table->index("cliente", "fk_cliente_has_pet_clientes_idx");
            $table->foreign("cliente", "fk_cliente_has_pet_clientes_idx")
                ->references("email")
                ->on("clientes")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->unsignedBigInteger('pet');
            $table->index('pet', 'fk_cliente_has_pet_pets_idx');
            $table->foreign('pet', 'fk_cliente_has_pet_pets_idx')
                ->references('codigo')
                ->on('pets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_has_pet');
    }
};
