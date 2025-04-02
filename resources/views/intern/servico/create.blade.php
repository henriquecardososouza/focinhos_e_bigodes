@extends("layout.app")

@section("title") | Administração | Serviços @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Cadastrar novo serviço</h1>

        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl mb-4">Novo serviço</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="tipo">Tipo</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="tipo" name="tipo">
                    @foreach(\App\Models\Servico::orderBy("tipo", "ASC")->get() as $servico)
                        <option value="{{ $servico->tipo }}">{{ $servico->tipo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="cliente">Cliente</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="cliente" name="cliente">
                    @foreach(\App\Models\Cliente::orderBy("nome", "ASC")->get() as $cliente)
                        <option value="{{ $cliente->email }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="pet">Pet</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="pet" name="pet">
                    @foreach(\App\Models\Pet::orderBy("nome", "ASC")->get() as $pet)
                        <option value="{{ $pet->codigo }}">{{ $pet->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="atendente">Atendente</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="atendente" name="atendente">
                    @foreach(\App\Models\Funcionario::orderBy("nome", "ASC")->get() as $funcionario)
                        <option value="{{ $funcionario->cpf }}">{{ $funcionario->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="prestador">Prestador</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="prestador" name="prestador">
                    @foreach(\App\Models\Funcionario::orderBy("nome", "ASC")->get() as $funcionario)
                        <option value="{{ $funcionario->cpf }}">{{ $funcionario->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="data">Data e hora</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="datetime-local" id="data" name="data">
            </div>

            <div class="flex gap-[5px] my-2">
                <input type="checkbox" id="buscar" name="buscar">
                <label for="buscar">Buscar pet na residência do cliente</label>
            </div>

            <div class="flex gap-[5px] my-2">
                <input type="checkbox" id="agendado" name="agendado">
                <label for="agendado">Serviço agendado</label>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="motorista">Motorista</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="motorista" name="motorista">
                    <option value="0">Nenhum</option>
                    @foreach(\App\Models\Funcionario::orderBy("nome", "ASC")->get() as $funcionario)
                        <option value="{{ $funcionario->cpf }}">{{ $funcionario->nome }}</option>
                    @endforeach
                </select>
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
                url: "{{ route("intern.servico.contratados.store") }}",
                method: "POST",
                data: {
                    servico: $("#tipo").val(),
                    cliente: $("#cliente").val(),
                    pet: $("#pet").val(),
                    atendente: $("#atendente").val(),
                    prestador: $("#prestador").val(),
                    motorista: $("#motorista").val() !== "0" ? $("#motorista").val() : undefined,
                    data: $("#data").val(),
                    hora: $("#hora").val(),
                    buscar: $("#buscar").is(":checked"),
                    agendado: $("#agendado").is(":checked"),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Serviço registrado", res.message);

                    setTimeout(() => {
                        location.href = res.url;
                    }, 1000);
                },
                error: function (err) {
                    showToastr("error", "Erro ao registrar", err.responseJSON.message);
                }
            })
        });
    </script>
@endpush
