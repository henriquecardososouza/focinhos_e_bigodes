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
        Schema::table("funcionarios", function (Blueprint $table) {
            $table->unsignedBigInteger("unidade")->after("cargo");
            $table->index("unidade", "fk_funcionarios_unidades_idx");
            $table->foreign("unidade", "fk_funcionarios_unidades_idx")
                ->references("endereco")
                ->on("unidades")
                ->onUpdate("cascade")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
