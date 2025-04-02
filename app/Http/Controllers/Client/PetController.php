<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Pet;
use App\Models\Vacina;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PetController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        return view("client.pets");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $cliente = Cliente::find(Auth::id());

            $pets = $cliente->pet()
                ->leftJoin("racas", "racas.nome", "=", "pets.raca")
                ->select(
                    "pets.codigo as codigo",
                    "pets.nome as nome",
                    env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(pets.data_nasc, '%d/%m/%Y') as data_nasc") : DB::raw("to_char(pets.data_nasc, 'DD/MM/YYYY') as data_nasc"),
                    "racas.nome as raca",
                    "racas.tipo as tipo",
                );

            return DataTables::of($pets)->editColumn('acao', function ($pet) {
                $buttons = "";

                $buttons .= '<a href="'.route("client.pet.vacina", $pet->codigo).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Vacinas</button>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\PetController::show()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function vacinas(Pet $pet): View
    {
        $pets = Pet::orderBy("nome", "ASC")->get();
        $vacinas = Vacina::orderBy("nome", "ASC")->get();

        return view("client.vacinas", compact("pet", "pets", "vacinas"));
    }

    public function dataVacinas(Pet $pet): JsonResponse
    {
        $vacinas = $pet->vacinas()->withPivot(["data", "dose"])
            ->select(
                "vacinas.nome as nome",
                "pet_has_vacina.dose as dose",
                env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(pet_has_vacina.data, '%d/%m/%Y') as data") : DB::raw("to_char(pet_has_vacina.data, 'DD/MM/YYYY') as data"),
            );

        return DataTables::of($vacinas)->toJson();
    }
}
