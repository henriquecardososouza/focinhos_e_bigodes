<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\TipoPet;
use App\Models\Unidade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UnidadeController extends Controller
{
    /**
     * Administrator CRUD for the model Unidade
     * @return View
     */
    public function show(): View
    {
        return view("intern.unidade.unidades");
    }

    public function data(): JsonResponse
    {
        try {
            $unidades = Unidade::
                query()
                ->leftJoin("enderecos", "enderecos.id", "=", "unidades.endereco")
                ->leftJoin("funcionarios", "funcionarios.cpf", "=", "unidades.gerente")
                ->select(
                    "unidades.endereco as endereco",
                    "funcionarios.nome as gerente",
                    "enderecos.rua",
                    "enderecos.numero",
                    "enderecos.bairro",
                    "enderecos.cidade",
                );

            $datatable = DataTables::of($unidades);

            $datatable->editColumn('gerente', function ($unidade) {
                if (!$unidade->gerente) {
                    return "Sem gerente";
                }

                return $unidade->gerente;
            });

            $datatable->editColumn('acao', function ($unidade) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.unidade.update', $unidade->endereco).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-unidade="'.$unidade->endereco.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            });

            $datatable->rawColumns(["acao"]);

            return $datatable->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\UnidadeController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.unidade.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "rua" => "required",
            "numero" => "required",
            "bairro" => "required",
            "cidade" => "required",
            "gerente" => "required",
        ], [
            "rua" => "Preencha o campo rua",
            "numero" => "Preencha o campo numero",
            "bairro" => "Preencha o campo bairro",
            "cidade" => "Preencha o campo cidade",
            "gerente.required" => "Preencha o campo gerente",
            "gerente.exists" => "Gerente não encontrado",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 400);
        }

        $validated = $validator->validated();

        if ($validated["gerente"] !== "-1") {
            if (!Funcionario::query()->find($validated["gerente"])) {
                return response()->json([
                    "message" => "Gerente não encontrado"
                ], 400);
            }
        }

        else {
            $validated["gerente"] = null;
        }

        $endereco = Endereco::query()
            ->create([
                "rua" => $validated["rua"],
                "numero" => $validated["numero"],
                "bairro" => $validated["bairro"],
                "cidade" => $validated["cidade"],
            ]);

        Unidade::query()
            ->create([
                "endereco" => $endereco->id,
                "gerente" => $validated["gerente"],
            ]);

        return response()->json([
            "message" => "Unidade criada com sucesso",
            "url" => route("intern.unidade.index")
        ]);
    }

    public function update(Unidade $unidade): View
    {
        $funcionarios = Funcionario::all(["cpf", "nome"]);
        return view("intern.unidade.edit", compact("unidade", "funcionarios"));
    }

    public function save(Request $request, Unidade $unidade): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "rua" => "required",
            "numero" => "required",
            "bairro" => "required",
            "cidade" => "required",
            "gerente" => "required",
        ], [
            "rua" => "Preencha o campo rua",
            "numero" => "Preencha o campo numero",
            "bairro" => "Preencha o campo bairro",
            "cidade" => "Preencha o campo cidade",
            "gerente.required" => "Preencha o campo gerente",
            "gerente.exists" => "Gerente não encontrado",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if ($validated["gerente"] !== "-1") {
            if (!Funcionario::query()->find($validated["gerente"])) {
                return response()->json([
                    "message" => "Gerente não encontrado"
                ], 400);
            }
        }

        else {
            $validated["gerente"] = null;
        }

        $unidade->dadosEndereco()->update([
            "rua" => $validated["rua"],
            "numero" => $validated["numero"],
            "bairro" => $validated["bairro"],
            "cidade" => $validated["cidade"],
        ]);

        $unidade->update([
            "gerente" => $validated["gerente"],
        ]);

        return response()->json([
            "message" => "Unidade atualizada com sucesso",
            "url" => route("intern.unidade.index")
        ]);
    }

    public function delete(Unidade $unidade): JsonResponse
    {
        Endereco::find($unidade->endereco)->delete();
        $unidade->delete();

        return response()->json([
            "message" => "Unidade excluída com sucesso"
        ]);
    }
}
