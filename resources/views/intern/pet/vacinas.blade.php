@extends("layout.app")

@section("title") | Administração | Pets | Vacinas @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Vacinas do pet {{ $pet->nome }}</h1>

        <section class="my-4">
            <table id="vacinas"></table>
        </section>

        <section class="my-6 flex justify-end" x-data="{ open: false }">
            <button @click="open=true" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Vincular vacina ao pet</button>
            <x-modal :id="'modal-vincular'">
                <div class="flex justify-between text-brown-500">
                    <h1 class="font-dosis text-2xl font-bold">Vincular vacina ao pet</h1>
                    <button @click="open=false" class="cursor-pointer hover:text-gray-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <label for="vacina-vinculo" class="mt-8 block">Vacina</label>
                <select class="w-full p-2 border rounded-xl bg-white" id="vacina-vinculo">
                    @foreach ($vacinas as $vacina)
                        <option value="{{ $vacina->nome }}">{{ $vacina->nome }}</option>
                    @endforeach
                </select>

                <label for="data" class="mt-2 block">Data</label>
                <input type="date" class="w-full p-2 border rounded-xl bg-white" id="data" name="data">

                <label for="dose" class="mt-4 block">Dose</label>
                <input type="number" class="w-full p-2 border rounded-xl mb-8 bg-white" id="dose" name="dose">

                <div class="flex justify-end">
                    <button data-pet="{{ $pet->codigo }}" class="btn-vincular rounded-xl max-w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Vincular</button>
                </div>
            </x-modal>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#vacinas").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.pet.vacina.data', $pet->codigo) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
                {
                    title: "Nome",
                    name: "nome",
                    data: "nome",
                },
                {
                    title: "Dose",
                    name: "dose",
                    data: "dose",
                },
                {
                    title: "Data",
                    name: "data",
                    data: "data",
                },
                {
                    title: "Ações",
                    name: "acao",
                    data: "acao",
                    searchable: false,
                    orderable: false
                }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            }
        });

        $("#vacinas").on("click", ".btn-delete", function (e) {
            const vacina = $(e.currentTarget).attr("data-vacina");

            if (confirm("Você realmente desvincular a vacina do pet?")) {
                $.ajax({
                    url: "{{ route('intern.pet.vacina.remove', [$pet->codigo, ':vacina']) }}".replace(":vacina", vacina),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Vacina removida", res.message);
                        $("#vacinas").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao remover", err.responseJSON.message);
                    }
                })
            }
        });

        $(".btn-vincular").on("click", function (e) {
            $.ajax({
                url: "{{ route('intern.pet.vacina.sync', [$pet->codigo, ':vacina']) }}".replace(':vacina', $("#vacina-vinculo").val()),
                method: 'POST',
                data: {
                    data: $("#data").val(),
                    dose: $("#dose").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Vacina vinculada", res.message);
                    $("#vacinas").DataTable().ajax.reload();
                },
                error: function (err) {
                    showToastr("error", "Erro ao vincular", err.responseJSON.message);
                }
            });
        });
    </script>
@endpush
