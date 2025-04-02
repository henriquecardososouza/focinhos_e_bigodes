<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\TipoPet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CargoController extends Controller
{
    /**
     * Administrator CRUD for the model Cargo
     * @return View
     */
    public function show(): View
    {
        return view("intern.cargo.cargos");
    }

    public function data(): JsonResponse
    {
        try {
            $cargos = Cargo::
                select(
                    "nome",
                    "salario",
                );

            return DataTables::of($cargos)->editColumn('acao', function ($cargo) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.cargo.update', $cargo->nome).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-cargo="'.$cargo->nome.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\CargoController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.cargo.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required|unique:cargos,nome",
            "salario" => "required|numeric",
        ], [
            "nome.required" => "Preencha o campo nome",
            "nome.unique" => "Já existe um cargo com esse nome",
            "salario.required" => "Preencha o campo salario",
            "salario.numeric" => "Informe um salário válido",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $validated["salario"] = $validated["salario"] * 100;

        Cargo::create($validated);

        return response()->json([
            "message" => "Cargo criado com sucesso",
            "url" => route("intern.cargo.index")
        ]);
    }

    public function update(Cargo $cargo): View
    {
        return view("intern.cargo.edit", compact("cargo"));
    }

    public function save(Request $request, Cargo $cargo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "salario" => "required|numeric",
        ], [
            "nome.required" => "Preencha o campo nome",
            "salario.required" => "Preencha o campo salario",
            "salario.numeric" => "Informe um salário válido",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $validated["salario"] = $validated["salario"] * 100;

        if ($validated["nome"] !== $cargo->nome && Cargo::find($validated["nome"])) {
            return response()->json([
                "message" => "O cargo informado já está cadastrado"
            ], 422);
        }

        $cargo->nome = $validated["nome"];
        $cargo->salario = $validated["salario"];
        $cargo->save();

        return response()->json([
            "message" => "Cargo atualizado com sucesso",
            "url" => route("intern.cargo.index")
        ]);
    }

    public function delete(Cargo $cargo): JsonResponse
    {
        $cargo->delete();

        return response()->json([
            "message" => "Cargo excluído com sucesso"
        ]);
    }
}
