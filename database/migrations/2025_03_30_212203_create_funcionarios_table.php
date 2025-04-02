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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->char("cpf", 11)->primary();
            $table->string("nome", 100);
            $table->char("telefone", 11);

            $table->string("cargo", 100);
            $table->index("cargo", "fk_funcionarios_cargos_idx");
            $table->foreign("cargo", "fk_funcionarios_cargos_idx")
                ->references("nome")
                ->on("cargos")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->string("credencial", 100);
            $table->index("credencial", "fk_funcionarios_credenciais_idx");
            $table->foreign("credencial", "fk_funcionarios_credenciais_idx")
                ->references("email")
                ->on("credenciais")
                ->onUpdate("cascade")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
