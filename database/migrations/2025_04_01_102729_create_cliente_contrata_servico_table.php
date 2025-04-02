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
        Schema::create("cliente_contrata_servico", function (Blueprint $table) {
            $table->id();

            $table->string("cliente", 150);
            $table->index("cliente", "fk_cliente_contrata_servico_cliente_idx");
            $table->foreign("cliente", "fk_cliente_contrata_servico_cliente_idx")
                ->references("email")
                ->on("clientes")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->string("servico", 100);
            $table->index("servico", "fk_cliente_contrata_servico_servico_idx");
            $table->foreign("servico", "fk_cliente_contrata_servico_servico_idx")
                ->references("tipo")
                ->on("servicos")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->char("funcionario", 11)->nullable();
            $table->index("funcionario", "fk_cliente_contrata_servico_funcionario_idx");
            $table->foreign("funcionario", "fk_cliente_contrata_servico_funcionario_idx")
                ->references("cpf")
                ->on("funcionarios")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->unsignedBigInteger("pet");
            $table->index("pet", "fk_cliente_contrata_servico_pet_idx");
            $table->foreign("pet", "fk_cliente_contrata_servico_pet_idx")
                ->references("codigo")
                ->on("pets")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->char("atendente", 11)->nullable();
            $table->index("atendente", "fk_cliente_contrata_servico_atendente_idx");
            $table->foreign("atendente", "fk_cliente_contrata_servico_atendente_idx")
                ->references("cpf")
                ->on("funcionarios")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            $table->dateTime("data");
            $table->boolean("estado")->default(false);
            $table->boolean("agendado")->default(false);
            $table->boolean("buscar_pet")->default(false);

            $table->char("motorista", 11)->nullable();
            $table->index("motorista", "fk_cliente_agenda_servico_motorista_idx");
            $table->foreign("motorista", "fk_cliente_agenda_servico_motorista_idx")
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
        Schema::dropIfExists("cliente_contrata_servico");
    }
};
