@extends("layout.app")

@section("title") | Administração | Vacinas @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <div class="flex flex-col justify-center align-center p-6 rounded-xl shadow-[0_0_20px_#00000055] max-w-[400px] w-[95%] mx-auto">
            <h1 class="text-center font-red-hat text-xl mb-4">Editar vacina</h1>
            <div class="flex flex-col gap-[5px] my-2">
                <label for="nome">Nome</label>
                <input class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="nome" name="nome" placeholder="Ex.: Antirrábica" value="{{ $vacina->nome }}" disabled>
            </div>

            <div class="flex flex-col gap-[5px] my-2">
                <label for="descricao">Descrição</label>
                <textarea placeholder="Descrição da vacina" class="border-1 border-orange-600 rounded-xl transition duration-400 py-2 px-3 focus:border-orange-800 focus:ring-0 focus:outline-0" type="text" id="descricao" name="descricao">{{ $vacina->descricao }}</textarea>
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
                url: "{{ route("intern.vacina.save", $vacina->nome) }}",
                method: "POST",
                data: {
                    nome: $("#nome").val(),
                    descricao: $("#descricao").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Vacina atualizada", res.message);

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
