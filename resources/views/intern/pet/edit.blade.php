@extends("layout.app")

@section("title") | Administração | Pets @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Editar dados do pet {{ $pet->nome }}</h1>

        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl mb-4">Editar pet</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="nome">Nome</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="nome" name="nome" placeholder="Ex.: Piriquito" value="{{ $pet->nome }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="date">Data nascimento</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="date" id="date" name="date" value="{{ (new \Carbon\Carbon($pet->data_nasc))->format("Y-m-d") }}">
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="raca">Raça</label>
                <select class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" id="raca" name="raca">
                    @foreach (\App\Models\Raca::orderBy("nome", "ASC")->get() as $raca)
                        <option value="{{ $raca->nome }}" @if ($raca->nome === $pet->raca) selected @endif>{{ $raca->nome }} | {{ $raca->tipo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-center items-center mt-4 flex-col">
                <button id="btn-editar" class="py-2 px-4 rounded-xl w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 cursor-pointer">Editar</button>
            </div>
        </div>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#btn-editar").on("click", function (e) {
            $.ajax({
                url: "{{ route("intern.pet.save", $pet->codigo) }}",
                method: "POST",
                data: {
                    nome: $("#nome").val(),
                    data_nasc: $("#date").val(),
                    raca: $("#raca").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Pet atualizado", res.message);

                    setTimeout(() => {
                        location.href = res.url;
                    }, 1000);
                },
                error: function (err) {
                    showToastr("error", "Erro ao atualizar", err.responseJSON.message);
                }
            })
        });
    </script>
@endpush
