<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use Yajra\DataTables\Facades\DataTables;

class ServicoController extends Controller
{
    /**
     * Administrator CRUD for the table cliente_contrata_servico
     * @return View
     */
    public function show(): View
    {
        return view("intern.servico.servicos");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $servicos = DB::table("cliente_contrata_servico")
                ->leftJoin("servicos", "servicos.tipo", "=", "cliente_contrata_servico.servico")
                ->leftJoin("pets", "pets.codigo", "=", "cliente_contrata_servico.pet")
                ->leftJoin("funcionarios", "funcionarios.cpf", "=", "cliente_contrata_servico.funcionario")
                ->leftJoin("funcionarios as atendente", "atendente.cpf", "=", "cliente_contrata_servico.atendente")
                ->leftJoin("clientes", "clientes.email", "=", "cliente_contrata_servico.cliente")
                ->select(
                    "cliente_contrata_servico.id",
                    "servicos.tipo as servico",
                    "pets.nome as pet",
                    "funcionarios.nome as funcionario",
                    "atendente.nome as atendente",
                    "clientes.nome as cliente",
                    DB::raw("IF(cliente_contrata_servico.agendado, 'Sim', 'Não') as agendado"),
                    DB::raw("IF(cliente_contrata_servico.estado, 'Realizado', 'Não realizado') as estado"),
                    env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(cliente_contrata_servico.data, '%d/%m/%Y') as data") : DB::raw("to_char(cliente_contrata_servico.data, 'DD/MM/YYYY') as data"),
                );

            return DataTables::of($servicos)->editColumn('acao', function ($servico) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.servico.contratados.update', $servico->id).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-servico="'.$servico->id.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                if ($servico->estado === "Não realizado") {
                    $buttons .= '<a data-servico="'.$servico->id.'" class="btn-complete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Completar</button>';
                }

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\FuncionarioController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.servico.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "servico" => "required|exists:servicos,tipo",
            "cliente" => "required|exists:clientes,email",
            "pet" => "required|exists:pets,codigo",
            "atendente" => "required|exists:funcionarios,cpf",
            "prestador" => "required|exists:funcionarios,cpf",
            "motorista" => "exists:funcionarios,cpf",
            "data" => 'required|date_format:"Y-m-d\TH:i"',
            "buscar" => "string|in:true,false",
            "agendado" => "string|in:true,false",
        ], [
            "servico.required" => "Preencha o campo nome",
            "servico.exists" => "Serviço não encontrado",
            "cliente.required" => "Preencha o campo cliente",
            "cliente.exists" => "Cliente não encontrado",
            "pet.required" => "Preencha o campo pet",
            "pet.exists" => "Pet não encontrado",
            "atendente.required" => "Preencha o campo atendente",
            "atendente.exists" => "Atendente não encontrado",
            "prestador.required" => "Preencha o campo prestador",
            "prestador.exists" => "Prestador não encontrado",
            "motorista.exists" => "Motorista não encontrado",
            "data.required" => "Preencha o campo data",
            "data.date_format" => "Informe um data válida",
            "buscar" => "Valor inválido para o campo buscar pet em residência",
            "agendado" => "Valor inválido para o campo agendar serviço",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        $cliente = Cliente::find($validated["cliente"]);

        if ($cliente->pet()->where("pets.codigo", $validated["pet"])->count() === 0) {
            return response()->json([
                "message" => "O pet não pertence ao cliente"
            ], 422);
        }

        DB::table('cliente_contrata_servico')->insert([
            "cliente" => $cliente->email,
            "servico" => $validated["servico"],
            "pet" => $validated["pet"],
            "funcionario" => $validated["prestador"],
            "atendente" => $validated["atendente"],
            "motorista" => $validated["motorista"] ?? null,
            "data" => $validated["data"],
            "buscar_pet" => isset($validated["buscar"]) && $validated["buscar"] == "true",
            "agendado" => isset($validated["agendado"]) && $validated["agendado"] == "true",
            "estado" => false,
        ]);

        /*$cliente->servicos()->syncWithoutDetaching([
            $validated["servico"] => [
                "pet" => $validated["pet"],
                "funcionario" => $validated["prestador"],
                "atendente" => $validated["atendente"],
                "motorista" => $validated["motorista"] ?? null,
                "data" => $validated["data"],
                "buscar_pet" => isset($validated["buscar"]) && $validated["buscar"] === "on",
                "agendado" => isset($validated["agendado"]) && $validated["agendado"] === "on",
                "estado" => false,
            ]
        ]);*/

        return response()->json([
            "message" => "Serviço registrado com sucesso",
            "url" => route("intern.servico.contratados.index")
        ]);
    }

    public function update($servico): View
    {
        if (!DB::table("cliente_contrata_servico")->where("id", $servico)->exists()) {
            abort(404, "Serviço não encontrado");
        }

        $servico = DB::table("cliente_contrata_servico")->where("id", $servico)->first();

        return view("intern.servico.edit", compact("servico"));
    }

    public function save(Request $request, $servico): JsonResponse
    {
        if (!DB::table("cliente_contrata_servico")->where("id", $servico)->exists()) {
            abort(404, "Serviço não encontrado");
        }

        $servico = DB::table("cliente_contrata_servico")->where("id", $servico)->first();

        $validator = Validator::make($request->all(), [
            "servico" => "required|exists:servicos,tipo",
            "cliente" => "required|exists:clientes,email",
            "pet" => "required|exists:pets,codigo",
            "atendente" => "required|exists:funcionarios,cpf",
            "prestador" => "required|exists:funcionarios,cpf",
            "motorista" => "exists:funcionarios,cpf",
            "data" => 'required|date_format:"Y-m-d\TH:i"',
            "buscar" => "string|in:true,false",
            "agendado" => "string|in:true,false",
        ], [
            "servico.required" => "Preencha o campo nome",
            "servico.exists" => "Serviço não encontrado",
            "cliente.required" => "Preencha o campo cliente",
            "cliente.exists" => "Cliente não encontrado",
            "pet.required" => "Preencha o campo pet",
            "pet.exists" => "Pet não encontrado",
            "atendente.required" => "Preencha o campo atendente",
            "atendente.exists" => "Atendente não encontrado",
            "prestador.required" => "Preencha o campo prestador",
            "prestador.exists" => "Prestador não encontrado",
            "motorista.exists" => "Motorista não encontrado",
            "data.required" => "Preencha o campo data",
            "data.date_format" => "Informe um data válida",
            "buscar" => "Valor inválido para o campo buscar pet em residência",
            "agendado" => "Valor inválido para o campo agendar serviço",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        $cliente = Cliente::find($validated["cliente"]);

        if ($cliente->pet()->where("pets.codigo", $validated["pet"])->count() === 0) {
            return response()->json([
                "message" => "O pet não pertence ao cliente"
            ], 422);
        }

        DB::table('cliente_contrata_servico')->where('id', $servico->id)->update([
            "cliente" => $cliente->email,
            "servico" => $validated["servico"],
            "pet" => $validated["pet"],
            "funcionario" => $validated["prestador"],
            "atendente" => $validated["atendente"],
            "motorista" => $validated["motorista"] ?? null,
            "data" => $validated["data"],
            "buscar_pet" => isset($validated["buscar"]) && $validated["buscar"] == 'true',
            "agendado" => isset($validated["agendado"]) && $validated["agendado"] == 'true',
            "estado" => false,
        ]);

        return response()->json([
            "message" => "Serviço editado com sucesso",
            "url" => route("intern.servico.contratados.index")
        ]);
    }

    public function delete($servico): JsonResponse
    {
        if (!DB::table("cliente_contrata_servico")->where("id", $servico)->exists()) {
            return response()->json([
                "message" => "Serviço não encontrado"
            ], 404);
        }

        DB::table("cliente_contrata_servico")->where("id", $servico)->delete();

        return response()->json([
            "message" => "Serviço excluído com sucesso"
        ]);
    }

    public function complete($servico): JsonResponse
    {
        if (!DB::table("cliente_contrata_servico")->where("id", $servico)->exists()) {
            return response()->json([
                "message" => "Serviço não encontrado"
            ], 404);
        }

        if (DB::table("cliente_contrata_servico")->where("id", $servico)->first()->estado) {
            return response()->json([
                "message" => "O serviço ja se encontra concluído"
            ], 422);
        }

        DB::table("cliente_contrata_servico")->where("id", $servico)->update([
            "estado" => true
        ]);

        return response()->json([
            "message" => "Serviço concluído com sucesso"
        ]);
    }
}
