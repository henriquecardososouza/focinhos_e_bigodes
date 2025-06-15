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
        Schema::create('unidades', function (Blueprint $table) {
            $table->unsignedBigInteger("endereco")->primary();
            $table->index("endereco", "fk_unidades_enderecos_idx");
            $table->foreign("endereco", "fk_unidades_enderecos_idx")
                ->references("id")
                ->on("enderecos")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->char("gerente", 11)->nullable();
            $table->index("gerente", "fk_unidades_funcionarios_idx");
            $table->foreign("gerente", "fk_unidades_funcionarios_idx")
                ->references("cpf")
                ->on("funcionarios")
                ->onUpdate("cascade")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
