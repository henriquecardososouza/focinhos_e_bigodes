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

class ServicosController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        return view("client.servico.servicos");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $cliente = Cliente::find(Auth::id());

            $servicos = DB::table("cliente_contrata_servico")
                ->leftJoin("servicos", "servicos.tipo", "=", "cliente_contrata_servico.servico")
                ->leftJoin("pets", "pets.codigo", "=", "cliente_contrata_servico.pet")
                ->leftJoin("funcionarios", "funcionarios.cpf", "=", "cliente_contrata_servico.funcionario")
                ->leftJoin("funcionarios as atendente", "atendente.cpf", "=", "cliente_contrata_servico.atendente")
                ->leftJoin("clientes", "clientes.email", "=", "cliente_contrata_servico.cliente")
                ->where("clientes.email", "LIKE", $cliente->email)
                ->select(
                    "cliente_contrata_servico.id",
                    "servicos.tipo as servico",
                    "pets.nome as pet",
                    "funcionarios.nome as funcionario",
                    "atendente.nome as atendente",
                    DB::raw("IF(cliente_contrata_servico.agendado, 'Sim', 'Não') as agendado"),
                    DB::raw("IF(cliente_contrata_servico.estado, 'Realizado', 'Não realizado') as estado"),
                    env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(cliente_contrata_servico.data, '%d/%m/%Y') as data") : DB::raw("to_char(cliente_contrata_servico.data, 'DD/MM/YYYY') as data"),
                );

            return DataTables::of($servicos)->toJson();
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
        return view("client.servico.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "servico" => "required|exists:servicos,tipo",
            "pet" => "required|exists:pets,codigo",
            "data" => 'required|date_format:"Y-m-d\TH:i"',
            "buscar" => "string|in:true,false",
        ], [
            "servico.required" => "Preencha o campo nome",
            "servico.exists" => "Serviço não encontrado",
            "pet.required" => "Preencha o campo pet",
            "pet.exists" => "Pet não encontrado",
            "data.required" => "Preencha o campo data",
            "data.date_format" => "Informe um data válida",
            "buscar" => "Valor inválido para o campo buscar pet em residência",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $cliente = Cliente::find(Auth::id());

        DB::table('cliente_contrata_servico')->insert([
            "cliente" => $cliente->email,
            "servico" => $validated["servico"],
            "pet" => $validated["pet"],
            "funcionario" => null,
            "atendente" => null,
            "motorista" => null,
            "data" => $validated["data"],
            "buscar_pet" => isset($validated["buscar"]) && $validated["buscar"] == "true",
            "agendado" => true,
            "estado" => false,
        ]);

        return response()->json([
            "message" => "Serviço agendado com sucesso",
            "url" => route("client.servico.index")
        ]);
    }
}
