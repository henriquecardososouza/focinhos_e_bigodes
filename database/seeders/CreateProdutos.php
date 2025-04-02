<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Produto;
use App\Models\Unidade;
use Illuminate\Database\Seeder;

class CreateProdutos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produtos = [
            [
                "nome" => "Ração Premium para Cães 10kg",
                "descricao" => "Alimento balanceado e nutritivo para cães adultos de todas as raças.",
                "valor" => 150.00,
                "estoque" => 20
            ],
            [
                "nome" => "Ração Super Premium para Gatos 5kg",
                "descricao" => "Ração rica em proteínas e vitaminas essenciais para gatos adultos.",
                "valor" => 130.00,
                "estoque" => 15
            ],
            [
                "nome" => "Brinquedo Mordedor de Borracha",
                "descricao" => "Mordedor resistente ideal para fortalecer a dentição de cães.",
                "valor" => 35.00,
                "estoque" => 30
            ],
            [
                "nome" => "Areia Higiênica para Gatos 4kg",
                "descricao" => "Areia absorvente com controle de odores para a higiene dos felinos.",
                "valor" => 40.00,
                "estoque" => 25
            ],
            [
                "nome" => "Coleira com Guia para Cães",
                "descricao" => "Coleira ajustável com guia reforçada para passeios seguros.",
                "valor" => 55.00,
                "estoque" => 10
            ],
            [
                "nome" => "Cama Macia para Pets",
                "descricao" => "Cama confortável e lavável para cães e gatos de porte médio.",
                "valor" => 120.00,
                "estoque" => 8
            ],
            [
                "nome" => "Shampoo Neutro para Pets",
                "descricao" => "Produto hipoalergênico ideal para banhos frequentes.",
                "valor" => 25.00,
                "estoque" => 40
            ],
            [
                "nome" => "Snack Natural para Cães 500g",
                "descricao" => "Petisco saudável feito com ingredientes naturais e sem conservantes.",
                "valor" => 45.00,
                "estoque" => 35
            ],
            [
                "nome" => "Arranhador para Gatos",
                "descricao" => "Arranhador de sisal para ajudar na manutenção das unhas dos gatos.",
                "valor" => 90.00,
                "estoque" => 12
            ],
            [
                "nome" => "Bebedouro Automático para Pets",
                "descricao" => "Fonte de água automática que estimula a hidratação dos animais.",
                "valor" => 110.00,
                "estoque" => 5
            ]
        ];

        foreach ($produtos as $produto) {
            Produto::updateOrCreate([
                "nome" => $produto["nome"]
            ], [
                "descricao" => $produto["descricao"],
                "valor" => $produto["valor"] * 100,
                "estoque" => $produto["estoque"]
            ]);
        }
    }
}
