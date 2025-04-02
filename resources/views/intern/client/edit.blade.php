@extends("layout.app")

@section("title") | Administração | Clientes @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Editar dados do cliente {{ $cliente->nome }}</h1>

        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl mb-4">Editar cliente</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="nome">Nome</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="nome" name="nome" required placeholder="Ex.: Joaquim da Silva" value="{{ $cliente->nome }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="email">E-mail</label>
                <input disabled class="border-1 bg-gray-100 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="email" id="email" name="email" required placeholder="Ex.: email@exemplo.com" value="{{ $cliente->email }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="telefone">Telefone</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="telefone" name="telefone" required placeholder="Ex.: 38992823645" value="{{ $cliente->telefone }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="rua">Rua</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="rua" name="rua" required placeholder="Ex.: Rua Santo Amaro" value="{{ $cliente->dadosEndereco->rua }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="numero">Número</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="numero" name="numero" required placeholder="Ex.: 375" value="{{ $cliente->dadosEndereco->numero }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="bairro">Bairro</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="bairro" name="bairro" required placeholder="Ex.: Centro" value="{{ $cliente->dadosEndereco->bairro }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="cidade">Cidade</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="cidade" name="cidade" required placeholder="Ex.: Vitória da Conquista" value="{{ $cliente->dadosEndereco->cidade }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="password">Senha</label>
                <div class="relative flex" x-data="{ hidden: true }">
                    <input class="border-1 w-full border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" x-bind:type="hidden ? 'password' : 'text'" id="password" name="password" required placeholder="******">
                    <i class="fas fa-eye absolute right-[10px] top-[50%] color-gray cursor-pointer transform-[translateY(-50%)]" @click="hidden = !hidden"></i>
                </div>
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
            data["rua"] = $("#rua").val();
            data["numero"] = $("#numero").val();
            data["bairro"] = $("#bairro").val();
            data["cidade"] = $("#cidade").val();
            data["_token"] = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route("intern.client.save", $cliente->email) }}",
                method: "POST",
                data: data,
                success: function (res) {
                    showToastr("success", "Cliente Editado", res.message);

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
