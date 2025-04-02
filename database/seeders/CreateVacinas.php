<?php

namespace Database\Seeders;

use App\Models\Vacina;
use Illuminate\Database\Seeder;

class CreateVacinas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacinas = [
            [
                "nome" => "V8",
                "descricao" => "Protege contra cinomose, parvovirose, hepatite infecciosa, coronavirose, leptospirose e adenovírus."
            ],
            [
                "nome" => "V10",
                "descricao" => "Similar à V8, mas com proteção ampliada contra mais sorovares de leptospirose."
            ],
            [
                "nome" => "Antirrábica",
                "descricao" => "Previne a raiva, uma doença fatal e zoonótica."
            ],
            [
                "nome" => "Giárdia",
                "descricao" => "Protege contra a infecção causada pelo protozoário Giardia spp."
            ],
            [
                "nome" => "Bordetella",
                "descricao" => "Reduz os sintomas da tosse dos canis, causada por Bordetella bronchiseptica."
            ],
            [
                "nome" => "Leishmaniose",
                "descricao" => "Ajuda na prevenção da leishmaniose visceral canina."
            ],
            [
                "nome" => "Felina Quádrupla",
                "descricao" => "Protege contra panleucopenia, rinotraqueíte, calicivirose e clamidiose."
            ],
            [
                "nome" => "Felina Quíntupla",
                "descricao" => "Além das doenças cobertas pela quádrupla, protege contra leucemia felina."
            ],
            [
                "nome" => "Brucelose",
                "descricao" => "Protege contra Brucella abortus e Brucella suis, prevenindo aborto infeccioso."
            ],
            [
                "nome" => "Febre Aftosa",
                "descricao" => "Previne contra a febre aftosa, uma doença altamente contagiosa."
            ],
            [
                "nome" => "Carbúnculo Sintomático",
                "descricao" => "Protege contra Clostridium chauvoei, que causa a 'manqueira negra'."
            ],
            [
                "nome" => "Raiva",
                "descricao" => "Essencial para prevenir a raiva em animais de grande porte"
            ],
            [
                "nome" => "Botulismo",
                "descricao" => "Protege contra toxinas produzidas por Clostridium botulinum."
            ],
            [
                "nome" => "Leptospirose",
                "descricao" => "Previne infecção por diversas espécies de Leptospira, que causam abortos e problemas reprodutivos."
            ],
            [
                "nome" => "Clostridioses",
                "descricao" => "Protege contra diversas infecções causadas por Clostridium spp., como enterotoxemia."
            ],
            [
                "nome" => "Influenza Equina",
                "descricao" => "Reduz os sintomas da gripe equina, uma doença respiratória contagiosa."
            ],
            [
                "nome" => "Herpesvírus Equino",
                "descricao" => "Protege contra rinopneumonite equina, causada pelo EHV-1 e EHV-4."
            ],
            [
                "nome" => "Pneumonia Enzoótica",
                "descricao" => "Protege contra Mycoplasma hyopneumoniae, responsável por doenças respiratórias em suínos."
            ],
            [
                "nome" => "Circovírus Suíno",
                "descricao" => "Previne infecção pelo PCV2, que causa síndrome multissistêmica do definhamento suíno."
            ],
            [
                "nome" => "Doença de Newcastle",
                "descricao" => "Protege contra um vírus altamente contagioso que afeta aves comerciais e silvestres."
            ]
        ];

        foreach ($vacinas as $dados) {
            Vacina::updateOrCreate([
                "nome" => $dados["nome"],
            ], [
                "descricao" => $dados["descricao"],
            ]);
        }
    }
}
