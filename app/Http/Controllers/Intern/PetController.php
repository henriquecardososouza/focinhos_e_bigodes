<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Pet;
use App\Models\Vacina;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        return view("intern.pet.pets");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $pets = Pet::
                leftJoin("racas", "racas.nome", "=", "pets.raca")
                ->select(
                    "pets.codigo as codigo",
                    "pets.nome as nome",
                    env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(pets.data_nasc, '%d/%m/%Y') as data_nasc") : DB::raw("to_char(pets.data_nasc, 'DD/MM/YYYY') as data_nasc"),
                    "racas.nome as raca",
                    "racas.tipo as tipo",
                );

            return DataTables::of($pets)->editColumn('acao', function ($pet) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.pet.update', $pet->codigo).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-pet="'.$pet->codigo.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';
                $buttons .= '<a href="'.route("intern.pet.vacina.index", $pet->codigo).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Vacinas</button>';

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

    public function create(): View
    {
        return view("intern.pet.create");
    }

    public function store(Request $request, Pet $pet): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "data_nasc" => "required|date",
            "raca" => "required|exists:racas,nome",
        ], [
            "nome" => "Preencha o campo nome",
            "date.required" => "Preencha o campo de data de nascimento",
            "date.date" => "Insira uma data de nascimento válida",
            "raca.required" => "Preencha o campo de raças",
            "raca.exists" => "Raça não encontrada",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        Pet::create($validated);

        return response()->json([
            "message" => "Pet criado com sucesso",
            "url" => route("intern.pet.index")
        ]);
    }

    public function update(Pet $pet): View
    {
        return view("intern.pet.edit", compact("pet"));
    }

    public function save(Request $request, Pet $pet): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "data_nasc" => "required|date",
            "raca" => "required|exists:racas,nome",
        ], [
            "nome" => "Preencha o campo nome",
            "date.required" => "Preencha o campo de data de nascimento",
            "date.date" => "Insira uma data de nascimento válida",
            "raca.required" => "Preencha o campo de raças",
            "raca.exists" => "Raça não encontrada",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        $pet->nome = $validated["nome"];
        $pet->data_nasc = $validated["data_nasc"];
        $pet->raca = $validated["raca"];
        $pet->save();

        return response()->json([
            "message" => "Pet atualizado com sucesso",
            "url" => route("intern.pet.index")
        ]);
    }

    public function delete(Pet $pet): JsonResponse
    {
        $pet->delete();

        return response()->json([
            "message" => "Pet excluído com sucesso"
        ]);
    }

    public function vacinas(Pet $pet): View
    {
        $pets = Pet::orderBy("nome", "ASC")->get();
        $vacinas = Vacina::orderBy("nome", "ASC")->get();

        return view("intern.pet.vacinas", compact("pet", "pets", "vacinas"));
    }

    public function dataVacinas(Pet $pet): JsonResponse
    {
        $vacinas = $pet->vacinas()->withPivot(["data", "dose"])
            ->select(
                "vacinas.nome as nome",
                "pet_has_vacina.dose as dose",
                env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(pet_has_vacina.data, '%d/%m/%Y') as data") : DB::raw("to_char(pet_has_vacina.data, 'DD/MM/YYYY') as data"),
            );

        return DataTables::of($vacinas)
            ->editColumn("acao", function ($vacina) use ($pet) {
                $buttons = "";

                $buttons .= '<a href="'.route("intern.vacina.update", $vacina->nome).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar vacina</a>';
                $buttons .= '<a data-vacina="'.$vacina->nome.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Desvincular vacina</a>';

                return $buttons;
            })
            ->rawColumns(['acao'])
            ->toJson();
    }

    public function addVacina(Request $request, Pet $pet, Vacina $vacina): JsonResponse
    {
        if (!$request->data) {
            return response()->json([
                "message" => "Preencha o campo de data"
            ], 422);
        }

        if (!$request->dose) {
            return response()->json([
                "message" => "Preencha o campo de dose"
            ], 422);
        }

        if ($pet->vacinas()->syncWithoutDetaching([
            $vacina->nome => [
                "data" => $request->data,
                "dose" => $request->dose
            ]
        ])) {
            return response()->json([
                "message" => "Vacina vinculada com sucesso"
            ]);
        }

        return response()->json([
            "message" => "Um erro inesperado ocorreu ao vincular a vacina"
        ], 500);
    }

    public function removeVacina(Pet $pet, Vacina $vacina): JsonResponse
    {
        if ($pet->vacinas()->detach([$vacina->nome])) {
            return response()->json([
                "message" => "Vacina desvinculada com sucesso"
            ]);
        }

        return response()->json([
            "message" => "Um erro inesperado ocorreu ao desvincular a vacina"
        ], 500);
    }
}
