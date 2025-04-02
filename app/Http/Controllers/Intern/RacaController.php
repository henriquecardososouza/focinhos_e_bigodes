<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Raca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class RacaController extends Controller
{
    /**
     * Administrator CRUD for the model Produto
     * @return View
     */
    public function show(): View
    {
        return view("intern.raca.racas");
    }

    public function data(): JsonResponse
    {
        try {
            $racas = Raca::
                select(
                    "racas.nome as nome",
                    "racas.tipo as tipo",
                );

            return DataTables::of($racas)->editColumn('acao', function ($raca) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.raca.update', $raca->nome).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-raca="'.$raca->nome.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\RacaController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.raca.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "tipo" => "required|exists:tipo_pets,nome",
        ], [
            "nome" => "Preencha o campo nome",
            "tipo.required" => "Preencha o campo tipo",
            "tipo.exists" => "Tipo de pet não encontrado",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        Raca::create($validated);

        return response()->json([
            "message" => "Raça criada com sucesso",
            "url" => route("intern.raca.index")
        ]);
    }

    public function update(Raca $raca): View
    {
        return view("intern.raca.edit", compact("raca"));
    }

    public function save(Request $request, Raca $raca): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "tipo" => "required|exists:tipo_pets,nome",
        ], [
            "tipo.required" => "Preencha o campo de tipo",
            "tipo.exists" => "Tipo de pet não encontrado",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $raca->tipo = $request->tipo;
        $raca->save();

        return response()->json([
            "message" => "Raça atualizada com sucesso",
            "url" => route("intern.raca.index")
        ]);
    }

    public function delete(Raca $raca): JsonResponse
    {
        $raca->delete();

        return response()->json([
            "message" => "Raça excluída com sucesso"
        ]);
    }
}
