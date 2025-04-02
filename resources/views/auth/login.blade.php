@extends("layout.app")

@section('title') | Entrar @endsection

@section('content')
    <x-navbar :renderSignIn="false" />

    <main class="my-24">
        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl">Acesse sua conta</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="email">E-mail</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="email" id="email" name="email" required placeholder="email@exemplo.com">
            </div>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="password">Senha</label>
                <div class="relative flex" x-data="{ hidden: true }">
                    <input class="border-1 w-full border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" x-bind:type="hidden ? 'password' : 'text'" id="password" name="password" required placeholder="******">
                    <i class="fas fa-eye absolute right-[10px] top-[50%] color-gray cursor-pointer transform-[translateY(-50%)]" @click="hidden = !hidden"></i>
                </div>
            </div>
            <div class="flex justify-end gap-[10px] text-sm">
                <label for="remember-me">Manter conectado</label>
                <input type="checkbox" id="remember-me" name="remember-me">
            </div>
            <div class="flex justify-center items-center mt-4 flex-col">
                <button id="btn-enter" class="py-2 px-4 rounded-xl w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 cursor-pointer">Entrar</button>
                <a href="{{ route('login.signup.show') }}" class="py-2 px-4 text-sm rounded-xl transition duration-400 text-gray-600 hover:text-black cursor-pointer w-[fit-content]">Não possui conta? Cadastrar-se</a>
            </div>
        </div>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#btn-enter").on("click", function (e) {
            const data = {};
            data['email'] = $("#email").val();

            if (data['email'].trim().length === 0) {
                showToastr("error", "Erro ao validar", "Preencha o campo de e-mail");
                return;
            }

            data['password'] = $("#password").val();

            if (data['password'].trim().length === 0) {
                showToastr("error", "Erro ao validar", "Preencha o campo de senha");
                return;
            }

            data['remember-me'] = $("#remember-me").is(":checked");
            data['_token'] = "{{ csrf_token() }}"

            $.ajax({
                url: "{{ route('login.attempt') }}",
                method: "POST",
                data: data,
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
