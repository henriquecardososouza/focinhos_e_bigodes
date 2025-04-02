<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Raca;
use App\Models\Venda;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class VendaController extends Controller
{
    /**
     * Administrator CRUD for the model Venda
     * @return View
     */
    public function show(): View
    {
        return view("intern.venda.vendas");
    }

    public function data(): JsonResponse
    {
        try {
            $vendas = Venda::
                leftJoin("clientes", "clientes.email", "=", "vendas.cliente")
                ->leftJoin("funcionarios", "funcionarios.cpf", "=", "vendas.funcionario")
                ->leftJoin("venda_has_produtos", "venda_has_produtos.venda", "=", "vendas.codigo")
                ->leftJoin("produtos", "produtos.codigo", "=", "venda_has_produtos.produto")
                ->select(
                    "vendas.codigo as codigo",
                    env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(vendas.data, '%d/%m/%Y %H:%i') as data") : DB::raw("to_char(vendas.data, 'DD/MM/YYYY HH:MI') as data"),
                    "clientes.nome as cliente",
                    "funcionarios.nome as funcionario",
                    DB::raw("CONCAT('R$ ', ROUND(SUM(produtos.valor * venda_has_produtos.quantidade) / 100, 2)) as valor"),
                )
            ->groupBy(
                "vendas.codigo",
                "data",
                "cliente",
                "funcionario",
                "valor"
            );

            return DataTables::of($vendas)->editColumn('acao', function ($venda) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.venda.update', $venda->codigo).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-venda="'.$venda->codigo.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';
                $buttons .= '<a href="'.route('intern.venda.produto.index', $venda->codigo).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Produtos</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\VendaController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.venda.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "cliente" => "required|exists:clientes,email",
            "funcionario" => "required|exists:funcionarios,cpf",
            "data" => "required|date_format:Y-m-d\TH:i",
        ], [
            "cliente.required" => "Preencha o campo cliente",
            "cliente.exists" => "Cliente não encontrado",
            "funcionairo.required" => "Preencha o campo funcionairo",
            "funcionario.exists" => "Funcionário não encontrado",
            "data.required" => "Preencha o campo data",
            "data.date_format" => "Informe uma data válida",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        Venda::create($validated);

        return response()->json([
            "message" => "Venda registrada com sucesso",
            "url" => route("intern.venda.index")
        ]);
    }

    public function update(Venda $venda): View
    {
        return view("intern.venda.edit", compact("venda"));
    }

    public function save(Request $request, Venda $venda): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "cliente" => "required|exists:clientes,email",
            "funcionario" => "required|exists:funcionarios,cpf",
            "data" => "required|date_format:Y-m-d\TH:i",
        ], [
            "cliente.required" => "Preencha o campo cliente",
            "cliente.exists" => "Cliente não encontrado",
            "funcionairo.required" => "Preencha o campo funcionairo",
            "funcionario.exists" => "Funcionário não encontrado",
            "data.required" => "Preencha o campo data",
            "data.date_format" => "Informe uma data válida",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $venda->update($validated);

        return response()->json([
            "message" => "Venda atualizada com sucesso",
            "url" => route("intern.venda.index")
        ]);
    }

    public function delete(Venda $venda): JsonResponse
    {
        $venda->delete();

        return response()->json([
            "message" => "Venda excluída com sucesso"
        ]);
    }

    public function produtos(Venda $venda): View
    {
        $produtos = Produto::all();
        return view("intern.venda.produtos", compact("venda", "produtos"));
    }

    public function dataProdutos(Venda $venda): JsonResponse
    {
        try {
            $produtos = $venda->produtos()
                ->select(
                    "produtos.codigo",
                    "produtos.nome",
                    "produtos.descricao",
                    "produtos.estoque",
                    "venda_has_produtos.quantidade",
                );

            return DataTables::of($produtos)->editColumn('acao', function ($produto) use ($venda) {
                $buttons = "";

                $buttons .= '<a href="'.route("intern.produto.update", $produto->codigo).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar produto</a>';
                $buttons .= '<a data-venda="'.$venda->codigo.'" data-produto="'.$produto->codigo.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Remover produto</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\VendaController::dataProdutos()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function removeProduto(Venda $venda, Produto $produto): JsonResponse
    {
        if ($venda->produtos()->detach([$produto->codigo])) {
            return response()->json([
                "message" => "Produto removido com sucesso"
            ]);
        }

        return response()->json([
            "message" => "Um erro iesperado ocorreu ao remover o produto"
        ], 500);
    }

    public function addProduto(Request $request, Venda $venda, Produto $produto): JsonResponse
    {
        if (!is_numeric($request->quantidade))  {
            return response()->json([
                "message" => "Informe uma quantidade válida"
            ], 422);
        }

        $quantidade = intval($request->quantidade);

        if ($quantidade <= 0) {
            return response()->json([
                "message" => "Informe uma quantidade válida"
            ], 422);
        }

        if ($quantidade > $produto->estoque) {
            return response()->json([
                "message" => "Quantidade indisponível, confira o estoque do produto"
            ], 422);
        }

        if ($venda->produtos()->syncWithoutDetaching([
            $produto->codigo => [
                "quantidade" => $request->quantidade
            ]
        ])) {
            $produto->decrement("estoque", $quantidade);
            return response()->json([
                "message" => "Produto adicionado com sucesso"
            ]);
        }

        return response()->json([
            "message" => "Um erro inesperado ocorreu ao adicionar o produto"
        ], 500);
    }
}
