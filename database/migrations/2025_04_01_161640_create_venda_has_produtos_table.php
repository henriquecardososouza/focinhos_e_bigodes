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
        Schema::create('venda_has_produtos', function (Blueprint $table) {
            $table->unsignedBigInteger('venda');
            $table->index('venda', 'fk_venda_has_produtos_venda_idx');
            $table->foreign('venda', 'fk_venda_has_produtos_venda_idx')
                ->references('codigo')
                ->on('vendas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('produto');
            $table->index('produto', 'fk_venda_has_produtos_produto_idx');
            $table->foreign('produto', 'fk_venda_has_produtos_produto_idx')
                ->references('codigo')
                ->on('produtos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('quantidade');
            $table->primary(['venda', 'produto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venda_has_produtos');
    }
};
