@extends("layout.app")

@section("title") | Administração | Unidades @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Cadastrar nova unidade</h1>

        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl mb-4">Cadastrar unidade</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="gerente">Gerente</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="gerente" name="gerente">
                    @foreach(\App\Models\Funcionario::orderBy("nome", "ASC")->get() as $funcionario)
                        <option value="{{ $funcionario->cpf }}">{{ $funcionario->nome }}</option>
                    @endforeach
                </select>
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

            <div class="flex justify-center items-center mt-4 flex-col">
                <button id="btn-cadastrar" class="py-2 px-4 rounded-xl w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 cursor-pointer">Cadastrar</button>
            </div>
        </div>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#btn-cadastrar").on("click", function (e) {
            $.ajax({
                url: "{{ route("intern.unidade.store") }}",
                method: "POST",
                data: {
                    gerente: $("#gerente").val(),
                    rua: $("#rua").val(),
                    numero: $("#numero").val(),
                    bairro: $("#bairro").val(),
                    cidade: $("#cidade").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Unidade cadastrada", res.message);

                    setTimeout(() => {
                        location.href = res.url;
                    }, 1000);
                },
                error: function (err) {
                    showToastr("error", "Erro ao cadastrar", err.responseJSON.message);
                }
            })
        });
    </script>
@endpush
