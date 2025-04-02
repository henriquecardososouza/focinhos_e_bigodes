<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Unidade;
use Illuminate\Database\Seeder;

class CreateUnidades extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $endereco = Endereco::create([
            "rua" => "Rosas azuis",
            "numero" => "45",
            "bairro" => "Centro",
            "cidade" => "Montes Claros"
        ]);


        $unidade = Unidade::create([
            "endereco" => $endereco->id
        ]);

        $credencial = Credencial::create([
            "email" => "funcionario_1@email",
            "password" => bcrypt("senha")
        ]);

        $cargo = Cargo::create([
            "nome" => "Vendedor",
            "salario" => 100000
        ]);

        $funcionario = Funcionario::create([
            "cpf" => "45387692863",
            "nome" => "Fernando",
            "telefone" => "7265748394",
            "cargo" => $cargo->nome,
            "unidade" => $unidade->endereco,
            "credencial" => $credencial->email
        ]);

        $unidade->gerente = $funcionario->cpf;
        $unidade->save();

    }
}
