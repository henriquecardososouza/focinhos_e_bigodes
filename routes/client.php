<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PetController;
use App\Http\Controllers\Client\ServicosController;

Route::group(["middleware" => "authenticated:landing,".\App\Models\Cliente::class.",email", "prefix" => "cliente", "as" => "client."], function () {
   Route::get("/", [HomeController::class, "show"])->name("home");
   Route::get("/pets", [PetController::class, "show"])->name("pets");
   Route::post("/pets", [PetController::class, "data"])->name("pets.data");
   Route::get("/pets/{pet}/vacinas", [PetController::class, "vacinas"])->name("pet.vacina");
   Route::post("/pets/{pet}/vacinas", [PetController::class, "dataVacinas"])->name("pet.vacina.data");

   Route::group(["prefix" => "servicos", "as" => "servico."], function () {
       Route::get("/", [ServicosController::class, "show"])->name("index");
       Route::post("/", [ServicosController::class, "data"])->name("data");
       Route::get("/agendar", [ServicosController::class, "create"])->name("create");
       Route::post("/agendar", [ServicosController::class, "store"])->name("store");
   });
});
