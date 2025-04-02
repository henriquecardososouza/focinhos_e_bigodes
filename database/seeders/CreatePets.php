<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Pet;
use App\Models\Raca;
use App\Models\TipoPet;
use App\Models\Unidade;
use Illuminate\Database\Seeder;

class CreatePets extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            "Cachorro" => [
                "Labrador Retriever",
                "Poodle",
                "Bulldog Francês",
                "Golden Retriever",
                "Dachshund",
            ],
            "Gato" => [
                "Persa",
                "Maine Coon",
                "Siamês",
                "Bengal",
                "Ragdoll",
            ],
            "Pássaro" => [
                "Calopsita",
                "Canário",
                "Periquito-australiano",
                "Agapornis",
                "Cacatua",
            ],
            "Coelho" => [
                "Holland Lop",
                "Mini Rex",
                "Angorá",
                "Lionhead",
                "Califórnia",
            ],
            "Répteis (Tartarugas e Jabutis)" => [
                "Tartaruga-Tigre-d’Água",
                "Tartaruga-de-Orelha-Vermelha",
                "Tartaruga-Russa",
                "Jabuti-Piranga",
                "Jabuti-Tinga",
            ]
        ];

        foreach ($tipos as $nome => $pets) {
            $tipo = TipoPet::firstOrCreate([
                "nome" => $nome
            ], []);

            foreach ($pets as $pet) {
                Raca::firstOrCreate([
                    "nome" => $pet,
                    "tipo" => $tipo->nome
                ]);
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $pet = Pet::create([
                "nome" => "pet ".$i,
                "data_nasc" => now(),
                "raca" => $tipos["Cachorro"][$i],
            ]);

            Cliente::first()->pet()->syncWithoutDetaching([$pet->codigo]);
        }
    }
}
