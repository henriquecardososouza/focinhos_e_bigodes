<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Unidade;
use Illuminate\Database\Seeder;

class CreateFuncionarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $endereco = Endereco::create([
            "rua" => "Rua 1",
            "numero" => "1",
            "bairro" => "Bairro",
            "cidade" => "Cidade",
        ]);

        $unidade = Unidade::create([
            "endereco" => $endereco->id
        ]);

        $credencial = Credencial::create([
           "email" => "admin@admin.com",
           "password" => bcrypt("123456")
        ]);

        $cargo = Cargo::create([
            "nome" => "Administrador",
            "salario" => 1000 * 100
        ]);

        Funcionario::create([
            "cpf" => "11111111111",
            "nome" => "Admin",
            "telefone" => "1234567890",
            "cargo" => $cargo->nome,
            "unidade" => $unidade->endereco,
            "credencial" => $credencial->email
        ]);
    }
}
