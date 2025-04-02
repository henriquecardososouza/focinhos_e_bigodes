<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\TipoPet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class TipoPetController extends Controller
{
    /**
     * Administrator CRUD for the model TipoPet
     * @return View
     */
    public function show(): View
    {
        return view("intern.tipo.tipos");
    }

    public function data(): JsonResponse
    {
        try {
            $tipos = TipoPet::
                select(
                    "tipo_pets.nome as nome",
                );

            return DataTables::of($tipos)->editColumn('acao', function ($tipo) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.tipo.update', $tipo->nome).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-tipo="'.$tipo->nome.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\TipoPetController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.tipo.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required|unique:tipo_pets,nome",
        ], [
            "nome" => "Preencha o campo nome",
            "unique" => "Este tipo de pet já está cadastrado",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        TipoPet::create($validated);

        return response()->json([
            "message" => "Tipo de pet criado com sucesso",
            "url" => route("intern.tipo.index")
        ]);
    }

    public function update(TipoPet $tipo): View
    {
        return view("intern.tipo.edit", compact("tipo"));
    }

    public function save(Request $request, TipoPet $tipo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
        ], [
            "nome" => "Preencha o campo nome",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if (TipoPet::find($validated["nome"])) {
            return response()->json([
                "message" => "O tipo informado já está cadastrado"
            ], 422);
        }

        $tipo->nome = $validated["nome"];
        $tipo->save();

        return response()->json([
            "message" => "Tipo de pet atualizada com sucesso",
            "url" => route("intern.tipo.index")
        ]);
    }

    public function delete(TipoPet $tipo): JsonResponse
    {
        $tipo->delete();

        return response()->json([
            "message" => "Tipo de pet excluído com sucesso"
        ]);
    }
}
