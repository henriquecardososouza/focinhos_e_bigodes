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

class VacinaController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        return view("intern.vacina.vacinas");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $vacinas = Vacina::
                select(
                    "vacinas.nome as nome",
                    "vacinas.descricao as descricao",
                );

            return DataTables::of($vacinas)->editColumn('acao', function ($vacina) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.vacina.update', $vacina->nome).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-vacina="'.$vacina->nome.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\VacinaController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.vacina.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "descricao" => "required",
        ], [
            "nome" => "Preencha o campo nome",
            "descricao" => "Preencha o campo descrição",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        Vacina::create($validated);

        return response()->json([
            "message" => "Vacina criada com sucesso",
            "url" => route("intern.vacina.index")
        ]);
    }

    public function update(Vacina $vacina): View
    {
        return view("intern.vacina.edit", compact("vacina"));
    }

    public function save(Request $request, Vacina $vacina): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "descricao" => "required",
        ], [
            "descricao" => "Preencha o campo descrição",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $vacina->descricao = $request->descricao;
        $vacina->save();

        return response()->json([
            "message" => "Vacina atualizada com sucesso",
            "url" => route("intern.vacina.index")
        ]);
    }

    public function delete(Vacina $vacina): JsonResponse
    {
        $vacina->delete();

        return response()->json([
            "message" => "Vacina excluída com sucesso"
        ]);
    }
}
