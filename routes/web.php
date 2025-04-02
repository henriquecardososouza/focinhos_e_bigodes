<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name("landing");
Route::get('/unidades', [LandingPageController::class, 'units'])->name("units");

Route::group(["as" => "login.", "middleware" => "notauthenticated:landing"], function () {
    Route::get("/entrar", [LoginController::class, "show"])->name("index");
    Route::post("/entrar", [LoginController::class, "store"])->name("attempt");
    Route::get("/cadastrar", [LoginController::class, "showSignup"])->name("signup.show");
    Route::post("/cadastrar", [LoginController::class, "storeSignup"])->name("signup.store");
});

Route::get("/sair", [LoginController::class, "logout"])->middleware("authenticated:landing")->name("logout");

include __DIR__."/client.php";
include __DIR__."/intern.php";
