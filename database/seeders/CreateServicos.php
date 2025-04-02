<?php

namespace Database\Seeders;

use App\Models\Servico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateServicos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicos = [
            "Banho" => [
                "descricao" => "Banho com carinho para seu pet! Higiene, hidratação e aquele cheirinho gostoso que seu amigo merece.",
                "valor" => 49.5
            ],
            "Tosa" => [
                "descricao" => "Tosa perfeita para seu pet! Estilo, conforto e cuidado para deixar seu amigo ainda mais bonito e feliz.",
                "valor" => 50
            ],
            "Pet walk" => [
                "descricao" => "Passeios divertidos e seguros para seu pet! Exercício, bem-estar e muita diversão ao ar livre.",
                "valor" => 10.40
            ],
            "Vacina" => [
                "descricao" => "Proteja quem você ama! Vacinação segura e essencial para a saúde do seu pet. Agende já e garanta o bem-estar do seu melhor amigo!",
                "valor" => 29.54
            ]
        ];

        foreach ($servicos as $servico => $attr) {
            Servico::updateOrCreate([
                "tipo" => $servico
            ], [
                "descricao" => $attr["descricao"],
                "valor" => $attr["valor"] * 100
            ]);
        }
    }
}
