<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Funcionario;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FuncionarioController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        return view("intern.funcionario.funcionarios");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $funcionarios = Funcionario::
                leftJoin("unidades", "unidades.endereco", "=", "funcionarios.unidade")
                ->leftJoin("enderecos", "enderecos.id", "=", "unidades.endereco")
                ->select(
                    "funcionarios.cpf as cpf",
                    "funcionarios.nome as nome",
                    "funcionarios.credencial as email",
                    "funcionarios.telefone as telefone",
                    "funcionarios.cargo as cargo",
                    "enderecos.bairro as unidade",
                );

            return DataTables::of($funcionarios)->editColumn('acao', function ($funcionario) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.funcionario.update', $funcionario->cpf).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-funcionario="'.$funcionario->cpf.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';
                $buttons .= '<a href="'.route("intern.funcionario.servico.index", $funcionario->cpf).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Serviços</button>';

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
        return view("intern.funcionario.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "email" => "required|email|unique:funcionarios,credencial",
            "telefone" => "required",
            "cpf" => "required|size:11",
            "cargo" => "required|exists:cargos,nome",
            "unidade" => "required|exists:unidades,endereco",
            "senha" => "required|min:6",
        ], [
            "nome" => "Preencha o campo nome",
            "email.required" => "Preencha o campo e-mail",
            "email.email" => "Insira um e-mail válido",
            "email.unique" => "O e-mail já está sendo utilizado",
            "senha.required" => "Preencha o campo senha",
            "senha.min" => "Insira ao menos 6 caracteres para a senha",
            "telefone" => "Preencha o campo telefone",
            "cpf.required" => "Preencha o campo CPF",
            "cpf.size" => "O campo de CPF deve ter 11 caracteres",
            "cargo.required" => "Preencha o campo cargo",
            "cargo.exists" => "Cargo não encontrado",
            "unidade.required" => "Preencha o campo unidade",
            "unidade.exists" => "Unidade não encontrada",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        $credencial = Credencial::create([
            "email" => $validated["email"],
            "password" => bcrypt($validated["senha"]),
        ]);

        Funcionario::create([
            "cpf" => $validated["cpf"],
            "nome" => $validated["nome"],
            "credencial" => $credencial->email,
            "telefone" => $validated["telefone"],
            "cargo" => $validated["cargo"],
            "unidade" => $validated["unidade"],
        ]);

        return response()->json([
            "message" => "Funcionário cadastrado com sucesso",
            "url" => route("intern.funcionario.index")
        ]);
    }

    public function update(Funcionario $funcionario): View
    {
        return view("intern.funcionario.edit", compact("funcionario"));
    }

    public function save(Request $request, Funcionario $funcionario): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "telefone" => "required",
            "cargo" => "required|exists:cargos,nome",
            "unidade" => "required|exists:unidades,endereco",
            "senha" => "min:6",
        ], [
            "nome" => "Preencha o campo nome",
            "email.required" => "Preencha o campo e-mail",
            "email.email" => "Insira um e-mail válido",
            "senha.required" => "Preencha o campo senha",
            "senha.min" => "Insira ao menos 6 caracteres para a senha",
            "telefone" => "Preencha o campo telefone",
            "cpf.required" => "Preencha o campo CPF",
            "cpf.size" => "O campo de CPF deve ter 11 caracteres",
            "cargo.required" => "Preencha o campo cargo",
            "cargo.exists" => "Cargo não encontrado",
            "unidade.required" => "Preencha o campo unidade",
            "unidade.exists" => "Unidade não encontrada",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $funcionario->nome = $validated["nome"];
        $funcionario->telefone = $validated["telefone"];
        $funcionario->cargo = $validated["cargo"];
        $funcionario->unidade = $validated["unidade"];
        $funcionario->save();


        if (isset($validated["senha"])) {
            $funcionario->credencial()->update([
                "password" => bcrypt($validated["senha"]),
            ]);
        }

        return response()->json([
            "message" => "Funcionário editado com sucesso",
            "url" => route("intern.funcionario.index")
        ]);
    }

    public function delete(Funcionario $funcionario): JsonResponse
    {
        $funcionario->delete();

        return response()->json([
            "message" => "Funcionário excluído com sucesso"
        ]);
    }

    public function servicos(Funcionario $funcionario): View
    {
        return view("intern.funcionario.servicos", compact("funcionario"));
    }

    public function dataServicos(Funcionario $funcionario): JsonResponse
    {
        $servicos = DB::table("cliente_contrata_servico")
            ->leftJoin("clientes", "clientes.email", "=", "cliente_contrata_servico.cliente")
            ->leftJoin("pets", "pets.codigo", "=", "cliente_contrata_servico.pet")
            ->where(function ($query) use ($funcionario) {
                $query->where("cliente_contrata_servico.funcionario", "LIKE", $funcionario->cpf);
                $query->orWhere("cliente_contrata_servico.atendente", "LIKE", $funcionario->cpf);
                $query->orWhere("cliente_contrata_servico.motorista", "LIKE", $funcionario->cpf);
            })
            ->select(
                "clientes.nome as cliente",
                "pets.nome as pet",
                DB::raw("IF (cliente_contrata_servico.agendado, 'Sim', 'Não') AS agendado"),
                DB::raw("IF (cliente_contrata_servico.estado, 'Realizado', 'Não realizado') AS estado"),
                env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(cliente_contrata_servico.data, '%d/%m/%Y') as data") : DB::raw("to_char(cliente_contrata_servico.data, 'DD/MM/YYYY') as data"),
            );

        return DataTables::of($servicos)
            ->editColumn('acao', function ($servico) {
                return "a";
            })
            ->rawColumns(['acao'])
            ->toJson();
    }
}
