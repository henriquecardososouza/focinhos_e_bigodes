<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Credencial;
use App\Models\Endereco;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * The login form view
     * @return View
     */
    public function show(): View
    {
        return view('auth.login');
    }

    /**
     * Logs in a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
            "rememeber-me" => "string|in:on,off"
        ], [
            "email" => "Email inválido"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first(),
            ], 422);
        }

        $validated = $validator->validated();

        $credencial = Credencial::find($validated["email"]);

        if (is_null($credencial) || !Hash::check($validated["password"], $credencial->password)) {
            return response()->json([
                "message" => "E-mail ou senha incorretos"
            ], 405);
        }

        Auth::attempt(["email" => $validated["email"], "password" => $validated["password"]], isset($validated["remember"]) && $validated["remember"]);

        if (\App\Models\Funcionario::where("credencial", "LIKE", \Illuminate\Support\Facades\Auth::id())->count() > 0) {
            return response()->json([
                "url" => route("intern.home")
            ]);
        }

        return response()->json([
            "url" => route("client.home")
        ]);
    }

    public function showSignup(): View
    {
        return view('auth.signup');
    }

    public function storeSignup(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "nome" => "required",
            "email" => "required|email|unique:clientes,email",
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
            "email.unique" => "Este e-mail já está cadastrado",
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
                "message" => $validator->errors()->first(),
            ], 422);
        }

        $validated = $validator->validated();

        $endereco = Endereco::create([
            "rua" => $validated["rua"],
            "numero" => $validated["numero"],
            "bairro" => $validated["bairro"],
            "cidade" => $validated["cidade"]
        ]);

        $credencial = Credencial::create([
            "email" => $validated["email"],
            "password" => $validated["senha"]
        ]);

        $cliente = Cliente::create([
            "nome" => $validated["nome"],
            "email" => $credencial->email,
            "telefone" => $validated["telefone"],
            "endereco" => $endereco->id
        ]);

        Auth::login($credencial);

        return response()->json([
            "url" => route("client.home")
        ]);
    }

    /**
     * Logs out a user
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('landing');
    }
}
