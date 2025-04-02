<?php

namespace Database\Seeders;

use App\Models\Servico;
use App\Models\Vacina;
use Illuminate\Database\Seeder;

class CreateServicos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicos = [
            [
                "tipo" => "Banho e Tosa",
                "descricao" => "Higienização completa com shampoo especial, secagem, escovação e tosa conforme a necessidade da raça.",
                "valor" => 50.00
            ],
            [
                "tipo" => "Consulta Veterinária",
                "descricao" => "Avaliação geral da saúde do pet, incluindo exames básicos e recomendações médicas.",
                "valor" => 120.00
            ],
            [
                "tipo" => "Vacinação",
                "descricao" => "Aplicação de vacinas essenciais para cães e gatos, como antirrábica e múltipla.",
                "valor" => 80.00
            ],
            [
                "tipo" => "Creche para Pets",
                "descricao" => "Espaço para socialização e cuidados diários, incluindo atividades recreativas e alimentação.",
                "valor" => 70.00
            ],
            [
                "tipo" => "Transporte Pet",
                "descricao" => "Serviço de transporte seguro para levar e buscar o pet em casa para consultas ou banho e tosa.",
                "valor" => 40.00
            ],
            [
                "tipo" => "Hidratação e Spa",
                "descricao" => "Tratamentos especiais para pelagem e pele, incluindo hidratação profunda e massagem relaxante.",
                "valor" => 90.00
            ]
        ];

        foreach ($servicos as $servico) {
            Servico::updateOrCreate([
                "tipo" => $servico["tipo"],
            ], [
                "descricao" => $servico["descricao"],
                "valor" => $servico["valor"] * 100,
            ]);
        }
    }
}
