<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Raca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProdutoController extends Controller
{
    /**
     * Administrator CRUD for the model Produto
     * @return View
     */
    public function show(): View
    {
        return view("intern.produto.produtos");
    }

    public function data(): JsonResponse
    {
        try {
            $produto = Produto::
                select(
                    "produtos.codigo",
                    "produtos.nome",
                    "produtos.descricao",
                    "produtos.valor",
                    "produtos.estoque",
                );

            return DataTables::of($produto)->editColumn('acao', function ($produto) {
                $buttons = "";

                $buttons .= '<a href="'.route('intern.produto.update', $produto->codigo).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-produto="'.$produto->codigo.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\ProdutoController::data()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function create(): View
    {
        return view("intern.produto.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "descricao" => "required",
            "valor" => "required|numeric|min:0",
            "estoque" => "required|numeric|min:0",
        ], [
            "nome" => "Preencha o campo nome",
            "descricao" => "Preencha o campo descrição",
            "valor.required" => "Preencha o campo valor",
            "valor.numeric" => "Insira um valor válido",
            "valor.min" => "Insira um valor válido",
            "estoque.required" => "Preencha o campo estoque",
            "estoque.numeric" => "Insira um estoque válido",
            "estoque.min" => "Insira um estoque válido",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $validated["valor"] = $validated["valor"] * 100;

        Produto::create($validated);

        return response()->json([
            "message" => "Produto criado com sucesso",
            "url" => route("intern.produto.index")
        ]);
    }

    public function update(Produto $produto): View
    {
        return view("intern.produto.edit", compact("produto"));
    }

    public function save(Request $request, Produto $produto): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "descricao" => "required",
            "valor" => "required|numeric|min:0",
            "estoque" => "required|numeric|min:0",
        ], [
            "nome" => "Preencha o campo nome",
            "descricao" => "Preencha o campo descrição",
            "valor.required" => "Preencha o campo valor",
            "valor.numeric" => "Insira um valor válido",
            "valor.min" => "Insira um valor válido",
            "estoque.required" => "Preencha o campo estoque",
            "estoque.numeric" => "Insira um estoque válido",
            "estoque.min" => "Insira um estoque válido",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $validated["valor"] = $validated["valor"] * 100;

        $produto->update($validated);

        return response()->json([
            "message" => "Produto atualizado com sucesso",
            "url" => route("intern.produto.index")
        ]);
    }

    public function delete(Produto $produto): JsonResponse
    {
        $produto->delete();

        return response()->json([
            "message" => "Produto excluído com sucesso"
        ]);
    }
}
