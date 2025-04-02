@extends("layout.app")

@section("title") | Administração | Funcionários @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Editar dados do funcionário {{ $funcionario->nome }}</h1>

        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl mb-4">Editar funcionário</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="nome">Nome</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="nome" name="nome" required placeholder="Ex.: Joaquim da Silva" value="{{ $funcionario->nome }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="email">E-mail</label>
                <input disabled class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="email" id="email" name="email" required placeholder="Ex.: email@exemplo.com" value="{{ $funcionario->credencial }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="telefone">Telefone</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="telefone" name="telefone" required placeholder="Ex.: 38992823645" value="{{ $funcionario->telefone }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="cpf">CPF</label>
                <input disabled class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="cpf" name="cpf" required placeholder="Ex.: 45356725859" value="{{ $funcionario->cpf }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="password">Senha</label>
                <div class="relative flex" x-data="{ hidden: true }">
                    <input class="border-1 w-full border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" x-bind:type="hidden ? 'password' : 'text'" id="password" name="password" required placeholder="******">
                    <i class="fas fa-eye absolute right-[10px] top-[50%] color-gray cursor-pointer transform-[translateY(-50%)]" @click="hidden = !hidden"></i>
                </div>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="cargo">Cargo</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="cargo" name="cargo">
                    @foreach(\App\Models\Cargo::orderBy("nome", "ASC")->get() as $cargo)
                        <option value="{{ $cargo->nome }}" @if ($cargo->nome === $funcionario->cargo) selected @endif>{{ $cargo->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="unidade">Unidade</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="unidade" name="unidade">
                    @foreach(\App\Models\Unidade::all() as $unidade)
                        <option value="{{ $unidade->endereco }}" @if ($unidade->endereco === $funcionario->unidade) selected @endif>{{ $unidade->dadosEndereco->bairro }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-center items-center mt-4 flex-col">
                <button id="btn-edit" class="py-2 px-4 rounded-xl w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 cursor-pointer">Editar</button>
            </div>
        </div>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#btn-edit").on("click", function (e) {
            const data = {};
            data["nome"] = $("#nome").val();
            data["email"] = $("#email").val();

            if ($("#password").val().length > 0) {
                data["senha"] = $("#password").val();
            }

            data["telefone"] = $("#telefone").val();
            data["cpf"] = $("#cpf").val();
            data["cargo"] = $("#cargo").val();
            data["unidade"] = $("#unidade").val();
            data["_token"] = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route("intern.funcionario.save", $funcionario->cpf) }}",
                method: "POST",
                data: data,
                success: function (res) {
                    showToastr("success", "Funcionário editado", res.message);

                    setTimeout(() => {
                        location.href = res.url;
                    }, 1000);
                },
                error: function (err) {
                    showToastr("error", "Erro ao editar", err.responseJSON.message);
                }
            })
        });
    </script>
@endpush
