@extends("layout.app")

@section('title') | Entrar @endsection

@section('content')
    <x-navbar :renderSignIn="false" />

    <main class="my-24">
        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl">Acesse sua conta</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="nome">Nome</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="nome" name="nome" required placeholder="Ex.: Joaquim da Silva">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="email">E-mail</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="email" id="email" name="email" required placeholder="Ex.: email@exemplo.com">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="telefone">Telefone</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="telefone" name="telefone" required placeholder="Ex.: 38992823645">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="rua">Rua</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="rua" name="rua" required placeholder="Ex.: Rua Santo Amaro">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="numero">Número</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="numero" name="numero" required placeholder="Ex.: 375">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="bairro">Bairro</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="bairro" name="bairro" required placeholder="Ex.: Centro">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="cidade">Cidade</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="cidade" name="cidade" required placeholder="Ex.: Vitória da Conquista">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="password">Senha</label>
                <div class="relative flex" x-data="{ hidden: true }">
                    <input class="border-1 w-full border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" x-bind:type="hidden ? 'password' : 'text'" id="password" name="password" required placeholder="******">
                    <i class="fas fa-eye absolute right-[10px] top-[50%] color-gray cursor-pointer transform-[translateY(-50%)]" @click="hidden = !hidden"></i>
                </div>
            </div>

            <div class="flex justify-center items-center mt-4 flex-col">
                <button id="btn-enter" class="py-2 px-4 rounded-xl w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 cursor-pointer">Cadastrar</button>
            </div>
        </div>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#btn-enter").on("click", function (e) {
            $.ajax({
                url: "{{ route('login.signup.show') }}",
                method: "POST",
                data: {
                    nome: $("#nome").val(),
                    email: $("#email").val(),
                    senha: $("#password").val(),
                    telefone: $("#telefone").val(),
                    rua: $("#rua").val(),
                    numero: $("#numero").val(),
                    bairro: $("#bairro").val(),
                    cidade: $("#cidade").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    location.href = res.url;
                },
                error: function (err) {
                    showToastr("error", "Erro ao validar", err.responseJSON.message);
                }
            })
        });
    </script>
@endpush
