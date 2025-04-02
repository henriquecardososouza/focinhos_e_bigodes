<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ClienteController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        return view("intern.client.clients");
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $clients = Cliente::
                leftJoin("enderecos", "enderecos.id", "=", "clientes.endereco")
                ->select(
                    "clientes.nome as nome",
                    "clientes.email as email",
                    "clientes.telefone as telefone",
                    DB::raw("CONCAT(enderecos.rua, ' - N°', enderecos.numero, ' - ', enderecos.bairro, ' / ', enderecos.cidade) as endereco"),
                );

            return DataTables::of($clients)->editColumn('acao', function ($client) {
                $buttons = "";
                $buttons .= '<a href="'.route('intern.client.update', $client->email).'" class="btn-edit rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar</a>';
                $buttons .= '<a data-client="'.$client->email.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Excluir</a>';
                $buttons .= '<a href="'.route("intern.client.pet.index", $client->email).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Pets</button>';
                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\ClientController::show()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function pets(Cliente $cliente): View
    {
        $pets = Pet::all();
        return view("intern.client.pets", compact("cliente", "cliente", "pets"));
    }

    public function dataPets(Request $request, Cliente $cliente): JsonResponse
    {
        try {
            $pets = $cliente->pet()
                ->leftJoin("racas", "racas.nome", "=", "pets.raca")
                ->select(
                    "pets.codigo as codigo",
                    "pets.nome as nome",
                    env("DB_CONNECTION") === "mysql" ? DB::raw("DATE_FORMAT(pets.data_nasc, '%d/%m/%Y') as data_nasc") : DB::raw("to_char(pets.data_nasc, 'DD/MM/YYYY') as data_nasc"),
                    "racas.nome as raca",
                    "racas.tipo as tipo",
                );

            return DataTables::of($pets)->editColumn('acao', function ($pet) use ($cliente) {
                $buttons = "";

                $buttons .= '<a href="'.route("intern.pet.update", $pet->codigo).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Editar Pet</a>';
                $buttons .= '<a data-client="'.$cliente->email.'" data-pet="'.$pet->codigo.'" class="btn-delete rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Remover Pet</a>';
                $buttons .= '<a href="'.route("intern.pet.vacina.index", $pet->codigo).'" class="rounded-lg mx-1 bg-orange-500 hover:bg-orange-600 transition duration-400 py-2 px-3 cursor-pointer">Vacinas</a>';

                return $buttons;
            })->rawColumns(["acao"])->toJson();
        }

        catch (\Exception $e) {
            Log::error("Error in Intern\ClientController::dataPets()", [$e]);

            return response()->json([
                "message" => "Um erro inesperado ocorreu"
            ], 500);
        }
    }

    public function removePet(Cliente $cliente, Pet $pet): JsonResponse
    {
        if ($cliente->pet()->detach([$pet->codigo])) {
            return response()->json([
                "message" => "Pet desvinculado com sucesso"
            ]);
        }

        return response()->json([
            "message" => "Um erro iesperado ocorreu ao desvincular o pet"
        ], 500);
    }

    public function addPet(Cliente $cliente, Pet $pet): JsonResponse
    {
        if ($cliente->pet()->syncWithoutDetaching([$pet->codigo])) {
            return response()->json([
                "message" => "Pet vinculado com sucesso"
            ]);
        }

        return response()->json([
            "message" => "Um erro iesperado ocorreu ao vincular o pet"
        ], 500);
    }

    public function create(): View
    {
        return view("intern.client.create");
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "email" => "required|email",
            "senha" => "required|min:6",
            "telefone" => "required",
            "rua" => "required",
            "numero" => "required",
            "bairro" => "required",
            "cidade" => "required",
        ], [
            "nome" => "Preencha o campo nome",
            "email.required" => "Preencha o campo e-mail",
            "email.email" => "Insira um e-mail válido",
            "senha.required" => "Preencha o campo senha",
            "senha.min" => "Insira ao menos 6 caracteres para a senha",
            "telefone" => "Preencha o campo telefone",
            "rua" => "Preencha o campo rua",
            "numero" => "Preencha o campo numero",
            "bairro" => "Preencha o campo bairro",
            "cidade" => "Preencha o campo cidade",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if (Credencial::find($validated["email"])) {
            return response()->json([
                "message" => "O e-mail informado já está sendo utilizado"
            ], 422);
        }

        $endereco = Endereco::firstOrCreate([
            "rua" => $validated["rua"],
            "numero" => $validated["numero"],
            "bairro" => $validated["bairro"],
            "cidade" => $validated["cidade"],
        ], []);

        $credencial = Credencial::create([
            "email" => $validated["email"],
            "password" => bcrypt($validated["senha"]),
        ]);

        Cliente::create([
            "email" => $credencial->email,
            "nome" => $request->input("nome"),
            "telefone" => $request->input("telefone"),
            "endereco" => $endereco->id
        ]);

        return response()->json([
            "message" => "Cliente cadastrado com sucesso",
            "url" => route("intern.client.index")
        ]);
    }

    public function update(Cliente $cliente): View
    {
        return view("intern.client.edit", compact("cliente"));
    }

    public function save(Request $request, Cliente $cliente): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "email" => "required|email",
            "senha" => "string|min:6",
            "telefone" => "required",
            "rua" => "required",
            "numero" => "required",
            "bairro" => "required",
            "cidade" => "required",
        ], [
            "nome" => "Preencha o campo nome",
            "email.required" => "Preencha o campo e-mail",
            "email.email" => "Insira um e-mail válido",
            "telefone" => "Preencha o campo telefone",
            "rua" => "Preencha o campo rua",
            "numero" => "Preencha o campo numero",
            "bairro" => "Preencha o campo bairro",
            "cidade" => "Preencha o campo cidade",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if (isset($validated["senha"])) {
            Credencial::where("email", "LIKE", $cliente->email)->update([
                "password" => bcrypt($validated["senha"])
            ]);
        }

        $endereco = $cliente->dadosEndereco()->update([
            "rua" => $validated["rua"],
            "numero" => $validated["numero"],
            "bairro" => $validated["bairro"],
            "cidade" => $validated["cidade"],
        ]);

        $cliente->update([
            "nome" => $request->input("nome"),
            "telefone" => $request->input("telefone"),
        ]);

        return response()->json([
            "message" => "Cliente editado com sucesso",
            "url" => route("intern.client.index")
        ]);
    }

    public function delete(Cliente $cliente): JsonResponse
    {
        $cliente->delete();

        return response()->json([
            "message" => "Cliente excluído com sucesso"
        ]);
    }
}
