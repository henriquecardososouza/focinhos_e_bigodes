<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use App\Models\TipoPet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class TipoServicoController extends Controller
{
    /**
     * Administrator CRUD for the model Servico
     * @return View
     */
    public function show(): View
    {
        return view("intern.tipo_servico.servicos");
    }

    public function data(): JsonResponse
    {
        try {
            $servicos = Servico::
                select(
                    "tipo",
                    "descricao",
                    "valor",
                );

            return DataTables::of($servicos)->editColumn('acao', function ($servico) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.servico.update', $servico->tipo).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-servico="'.$servico->tipo.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\TipoServicoController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.tipo_servico.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "tipo" => "required|unique:tipo_pets,nome",
            "descricao" => "required",
            "valor" => "required|numeric"
        ], [
            "tipo.required" => "Preencha o campo tipo",
            "tipo.unique" => "Este tipo já está utilizando",
            "valor.required" => "Preencha o campo valor",
            "valor.numeric" => "Insira um valor válido",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if ($validated["valor"] < 0) {
            return response()->json([
                "message" => "Insira um valor válido"
            ], 422);
        }

        Servico::create($validated);

        return response()->json([
            "message" => "Serviço criado com sucesso",
            "url" => route("intern.servico.index")
        ]);
    }

    public function update(Servico $servico): View
    {
        return view("intern.tipo_servico.edit", compact("servico"));
    }

    public function save(Request $request, Servico $servico): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "tipo" => "required|unique:tipo_pets,nome",
            "descricao" => "required",
            "valor" => "required|numeric"
        ], [
            "tipo.required" => "Preencha o campo tipo",
            "tipo.unique" => "Este tipo já está utilizando",
            "valor.required" => "Preencha o campo valor",
            "valor.numeric" => "Insira um valor válido",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if ($validated["valor"] < 0) {
            return response()->json([
                "message" => "Insira um valor válido"
            ], 422);
        }

        if (Servico::where("tipo", "LIKE", $validated["tipo"])->count() > 1) {
            return response()->json([
                "message" => "O tipo informado já está cadastrado"
            ], 422);
        }

        $servico->tipo = $validated["tipo"];
        $servico->descricao = $validated["descricao"];
        $servico->valor = round($validated["valor"], 2) * 100;
        $servico->save();

        return response()->json([
            "message" => "Serviço atualizado com sucesso",
            "url" => route("intern.servico.index")
        ]);
    }

    public function delete(Servico $servico): JsonResponse
    {
        $servico->delete();

        return response()->json([
            "message" => "Serviço excluído com sucesso"
        ]);
    }
}
