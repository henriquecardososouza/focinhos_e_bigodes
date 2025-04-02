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
        Schema::create('vendas', function (Blueprint $table) {
            $table->unsignedBigInteger('codigo')->primary()->autoIncrement();

            $table->string('cliente', 150);
            $table->index('cliente', 'fk_vendas_cliente_idx');
            $table->foreign('cliente', 'fk_vendas_cliente_idx')
                ->references('email')
                ->on('clientes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->char('funcionario', 11);
            $table->index('funcionario', 'fk_vendas_funcionario_idx');
            $table->foreign('funcionario', 'fk_vendas_funcionario_idx')
                ->references('cpf')
                ->on('funcionarios')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dateTime("data");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
