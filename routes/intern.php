<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Intern\HomeController;
use App\Http\Controllers\Intern\ClienteController;
use App\Http\Controllers\Intern\PetController;
use App\Http\Controllers\Intern\VacinaController;
use App\Http\Controllers\Intern\FuncionarioController;
use App\Http\Controllers\Intern\ServicoController;
use App\Http\Controllers\Intern\RacaController;
use App\Http\Controllers\Intern\TipoPetController;
use App\Http\Controllers\Intern\ProdutoController;
use App\Http\Controllers\Intern\VendaController;
use App\Http\Controllers\Intern\TipoServicoController;
use App\Http\Controllers\Intern\CargoController;
use App\Http\Controllers\Intern\UnidadeController;

Route::group(["middleware" => "authenticated:landing,".\App\Models\Funcionario::class, "prefix" => "administracao", "as" => "intern."], function () {
    Route::get("/", [HomeController::class, "show"])->name("home");

    Route::group(["prefix" => "clientes", "as" => "client."], function () {
        Route::get("/", [ClienteController::class, "show"])->name("index");
        Route::post("/", [ClienteController::class, "data"])->name("data");
        Route::get("/criar", [ClienteController::class, "create"])->name("create");
        Route::post("/criar", [ClienteController::class, "store"])->name("store");
        Route::get("/{cliente}/editar", [ClienteController::class, "update"])->name("update");
        Route::post("/{cliente}/editar", [ClienteController::class, "save"])->name("save");
        Route::delete("/{cliente}/excluir", [ClienteController::class, "delete"])->name("delete");

        Route::group(["prefix" => "pets", "as" => "pet."], function () {
           Route::get("/{cliente}", [ClienteController::class, "pets"])->name("index");
           Route::post("/{cliente}", [ClienteController::class, "dataPets"])->name("data");
           Route::post("/{cliente}/{pet}/adicionar", [ClienteController::class, "addPet"])->name("sync");
           Route::delete("/{cliente}/{pet}/remover", [ClienteController::class, "removePet"])->name("remove");
        });
    });

    Route::group(["prefix" => "funcionarios", "as" => "funcionario."], function () {
        Route::get("/", [FuncionarioController::class, "show"])->name("index");
        Route::post("/", [FuncionarioController::class, "data"])->name("data");
        Route::get("/criar", [FuncionarioController::class, "create"])->name("create");
        Route::post("/criar", [FuncionarioController::class, "store"])->name("store");
        Route::get("/{funcionario}/editar", [FuncionarioController::class, "update"])->name("update");
        Route::post("/{funcionario}/editar", [FuncionarioController::class, "save"])->name("save");
        Route::delete("/{funcionario}/excluir", [FuncionarioController::class, "delete"])->name("delete");

        Route::group(["prefix" => "servicos", "as" => "servico."], function () {
            Route::get("/{funcionario}", [FuncionarioController::class, "servicos"])->name("index");
            Route::post("/{funcionario}", [FuncionarioController::class, "dataServicos"])->name("data");
            Route::post("/{funcionario}/{servico}/adicionar", [FuncionarioController::class, "addServico"])->name("sync");
            Route::delete("/{funcionario}/{servico}/remover", [FuncionarioController::class, "removeServico"])->name("remove");
        });
    });

    Route::group(["prefix" => "cargos", "as" => "cargo."], function () {
        Route::get("/", [CargoController::class, "show"])->name("index");
        Route::post("/", [CargoController::class, "data"])->name("data");
        Route::get("/criar", [CargoController::class, "create"])->name("create");
        Route::post("/criar", [CargoController::class, "store"])->name("store");
        Route::get("/{cargo}/editar", [CargoController::class, "update"])->name("update");
        Route::post("/{cargo}/editar", [CargoController::class, "save"])->name("save");
        Route::delete("/{cargo}/excluir", [CargoController::class, "delete"])->name("delete");
    });

    Route::group(["prefix" => "pets", "as" => "pet."], function () {
        Route::get("/", [PetController::class, "show"])->name("index");
        Route::post("/", [PetController::class, "data"])->name("data");
        Route::get("/criar", [PetController::class, "create"])->name("create");
        Route::post("/criar", [PetController::class, "store"])->name("store");
        Route::get("/{pet}/editar", [PetController::class, "update"])->name("update");
        Route::post("/{pet}/editar", [PetController::class, "save"])->name("save");
        Route::delete("/{pet}/excluir", [PetController::class, "delete"])->name("delete");

        Route::group(["prefix" => "vacinas", "as" => "vacina."], function () {
            Route::get("/{pet}", [PetController::class, "vacinas"])->name("index");
            Route::post("/{pet}", [PetController::class, "dataVacinas"])->name("data");
            Route::post("/{pet}/{vacina}/adicionar", [PetController::class, "addVacina"])->name("sync");
            Route::delete("/{pet}/{vacina}/remover", [PetController::class, "removeVacina"])->name("remove");
        });
    });

    Route::group(["prefix" => "vacinas", "as" => "vacina."], function () {
        Route::get("/", [VacinaController::class, "show"])->name("index");
        Route::post("/", [VacinaController::class, "data"])->name("data");
        Route::get("/criar", [VacinaController::class, "create"])->name("create");
        Route::post("/criar", [VacinaController::class, "store"])->name("store");
        Route::get("/{vacina}/editar", [VacinaController::class, "update"])->name("update");
        Route::post("/{vacina}/editar", [VacinaController::class, "save"])->name("save");
        Route::delete("/{vacina}/excluir", [VacinaController::class, "delete"])->name("delete");
    });

    Route::group(["prefix" => "servicos/contratados", "as" => "servico.contratados."], function () {
        Route::get("/", [ServicoController::class, "show"])->name("index");
        Route::post("/", [ServicoController::class, "data"])->name("data");
        Route::get("/criar", [ServicoController::class, "create"])->name("create");
        Route::post("/criar", [ServicoController::class, "store"])->name("store");
        Route::get("/{servico}/editar", [ServicoController::class, "update"])->name("update");
        Route::post("/{servico}/editar", [ServicoController::class, "save"])->name("save");
        Route::delete("/{servico}/excluir", [ServicoController::class, "delete"])->name("delete");
        Route::post("/{servico}/completar", [ServicoController::class, "complete"])->name("complete");

        Route::group(["prefix" => "servicos", "as" => "servico."], function () {
            Route::get("/{funcionario}", [ServicoController::class, "servicos"])->name("index");
            Route::post("/{funcionario}", [ServicoController::class, "dataServicos"])->name("data");
            Route::post("/{funcionario}/{servico}/adicionar", [ServicoController::class, "addServico"])->name("sync");
            Route::delete("/{funcionario}/{servico}/remover", [ServicoController::class, "removeServico"])->name("remove");
        });
    });

    Route::group(["prefix" => "servicos", "as" => "servico."], function () {
        Route::get("/", [TipoServicoController::class, "show"])->name("index");
        Route::post("/", [TipoServicoController::class, "data"])->name("data");
        Route::get("/criar", [TipoServicoController::class, "create"])->name("create");
        Route::post("/criar", [TipoServicoController::class, "store"])->name("store");
        Route::get("/{servico}/editar", [TipoServicoController::class, "update"])->name("update");
        Route::post("/{servico}/editar", [TipoServicoController::class, "save"])->name("save");
        Route::delete("/{servico}/excluir", [TipoServicoController::class, "delete"])->name("delete");
    });

    Route::group(["prefix" => "racas", "as" => "raca."], function () {
        Route::get("/", [RacaController::class, "show"])->name("index");
        Route::post("/", [RacaController::class, "data"])->name("data");
        Route::get("/criar", [RacaController::class, "create"])->name("create");
        Route::post("/criar", [RacaController::class, "store"])->name("store");
        Route::get("/{raca}/editar", [RacaController::class, "update"])->name("update");
        Route::post("/{raca}/editar", [RacaController::class, "save"])->name("save");
        Route::delete("/{raca}/excluir", [RacaController::class, "delete"])->name("delete");
    });

    Route::group(["prefix" => "tipos", "as" => "tipo."], function () {
        Route::get("/", [TipoPetController::class, "show"])->name("index");
        Route::post("/", [TipoPetController::class, "data"])->name("data");
        Route::get("/criar", [TipoPetController::class, "create"])->name("create");
        Route::post("/criar", [TipoPetController::class, "store"])->name("store");
        Route::get("/{tipo}/editar", [TipoPetController::class, "update"])->name("update");
        Route::post("/{tipo}/editar", [TipoPetController::class, "save"])->name("save");
        Route::delete("/{tipo}/excluir", [TipoPetController::class, "delete"])->name("delete");
    });

    Route::group(["prefix" => "produtos", "as" => "produto."], function () {
        Route::get("/", [ProdutoController::class, "show"])->name("index");
        Route::post("/", [ProdutoController::class, "data"])->name("data");
        Route::get("/criar", [ProdutoController::class, "create"])->name("create");
        Route::post("/criar", [ProdutoController::class, "store"])->name("store");
        Route::get("/{produto}/editar", [ProdutoController::class, "update"])->name("update");
        Route::post("/{produto}/editar", [ProdutoController::class, "save"])->name("save");
        Route::delete("/{produto}/excluir", [ProdutoController::class, "delete"])->name("delete");
    });

    Route::group(["prefix" => "vendas", "as" => "venda."], function () {
        Route::get("/", [VendaController::class, "show"])->name("index");
        Route::post("/", [VendaController::class, "data"])->name("data");
        Route::get("/criar", [VendaController::class, "create"])->name("create");
        Route::post("/criar", [VendaController::class, "store"])->name("store");
        Route::get("/{venda}/editar", [VendaController::class, "update"])->name("update");
        Route::post("/{venda}/editar", [VendaController::class, "save"])->name("save");
        Route::delete("/{venda}/excluir", [VendaController::class, "delete"])->name("delete");

        Route::group(["prefix" => "produtos", "as" => "produto."], function () {
            Route::get("/{venda}", [VendaController::class, "produtos"])->name("index");
            Route::post("/{venda}", [VendaController::class, "dataProdutos"])->name("data");
            Route::post("/{venda}/{produto}/adicionar", [VendaController::class, "addProduto"])->name("sync");
            Route::delete("/{venda}/{produto}/remover", [VendaController::class, "removeProduto"])->name("remove");
        });
    });

    Route::group(["prefix" => "unidades", "as" => "unidade."], function () {
        Route::get("/", [UnidadeController::class, "show"])->name("index");
        Route::post("/", [UnidadeController::class, "data"])->name("data");
        Route::get("/criar", [UnidadeController::class, "create"])->name("create");
        Route::post("/criar", [UnidadeController::class, "store"])->name("store");
        Route::get("/{unidade}/editar", [UnidadeController::class, "update"])->name("update");
        Route::post("/{unidade}/editar", [UnidadeController::class, "save"])->name("save");
        Route::delete("/{unidade}/excluir", [UnidadeController::class, "delete"])->name("delete");
    });
});
