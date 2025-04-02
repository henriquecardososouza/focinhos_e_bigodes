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
        Schema::create('credenciais', function (Blueprint $table) {
            $table->string('email', 150)->primary();
            $table->string('password', 100);
            $table->rememberToken();
            $table->datetime('email_verificado_em')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 150)->primary();
            $table->index('email', 'fk_password_resets_credenciais_idx');
            $table->foreign('email', 'fk_password_resets_credenciais_idx')
                ->references('email')
                ->on('credenciais')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('token', 200);
            $table->datetime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credenciais');
        Schema::dropIfExists('password_reset_tokens');
    }
};
