<?php

namespace Database\Seeders;

use App\Models\Raca;
use App\Models\TipoPet;
use Illuminate\Database\Seeder;

class CreateTiposPet extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            "Cachorros" => [
                "Labrador Retriever",
                "Poodle",
                "Bulldog Francês",
                "Golden Retriever",
                "Dachshund"
            ],
            "Gatos" => [
                "Persa",
                "Maine Coon",
                "Siamês",
                "Bengal",
                "Ragdoll",
            ],
            "Pássaros" => [
                "Calopsita",
                "Canário",
                "Periquito-australiano",
                "Agapornis",
                "Cacatua",
            ],
            "Coelhos" => [
                "Holland Lop",
                "Mini Rex",
                "Angorá",
                "Lionhead",
                "Califórnia"
            ],
            "Répteis (Tartarugas e Jabutis)" => [
                "Tartaruga-Tigre-d’Água",
                "Tartaruga-de-Orelha-Vermelha",
                "Tartaruga-Russa",
                "Jabuti-Piranga",
                "Jabuti-Tinga"
            ]
        ];

        foreach ($tipos as $tipo => $racas) {
            $model = TipoPet::updateOrCreate([
                "nome" => $tipo,
            ]);

            foreach ($racas as $raca) {
                Raca::updateOrCreate([
                    "nome" => $raca,
                ], [
                    "tipo" => $model->nome,
                ]);
            }
        }
    }
}
