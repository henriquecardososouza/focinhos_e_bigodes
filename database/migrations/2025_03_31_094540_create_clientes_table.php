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
        Schema::create('clientes', function (Blueprint $table) {
            $table->string('email', 150)->primary();
            $table->index('email', 'fk_clientes_credenciais_idx');
            $table->foreign('email', 'fk_clientes_credenciais_idx')
                ->references('email')
                ->on('credenciais')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('nome', 100);
            $table->string('telefone', 100);
            $table->unsignedBigInteger('endereco');
            $table->index('endereco', 'fk_clientes_endereco_idx');
            $table->foreign('endereco', 'fk_clientes_endereco_idx')
                ->references('id')
                ->on('enderecos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
