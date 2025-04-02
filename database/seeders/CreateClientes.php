<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use Illuminate\Database\Seeder;

class CreateClientes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $credencial = Credencial::create([
                "email" => "cliente".$i."@gmail.com",
                "password" => "senha",
            ]);

            Cliente::create([
                "email" => $credencial->email,
                "nome" => "cliente ".$i,
                "telefone" => "38474638223",
                "endereco" => Endereco::first()->id
            ]);
        }
    }
}
