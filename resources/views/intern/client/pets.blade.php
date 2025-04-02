@extends("layout.app")

@section("title") | Administração | Clientes | Pets @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Pets do cliente {{ $cliente->nome }}</h1>

        <section class="my-4">
            <table id="pets"></table>
        </section>

        <section class="my-6 flex justify-end" x-data="{ open: false }">
            <button @click="open=true" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Vincular pet ao cliente</button>
            <x-modal :id="'modal-vincular'">
                <div class="flex justify-between text-brown-500">
                    <h1 class="font-dosis text-2xl font-bold">Vincular pet ao cliente</h1>
                    <button @click="open=false" class="cursor-pointer hover:text-gray-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <select class="w-full p-2 border rounded-xl bg-white my-8" id="pet-vinculo">
                    @foreach ($pets as $pet)
                        <option value="{{ $pet->codigo }}">{{ $pet->nome }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end">
                    <button data-cliente="{{ $cliente->email }}" class="btn-vincular rounded-xl max-w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Vincular</button>
                </div>
            </x-modal>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#pets").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.client.pet.data', $cliente->email) }}",
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
                    title: "Data nascimento",
                    name: "data_nasc",
                    data: "data_nasc",
                },
                {
                    title: "Raça",
                    name: "raca",
                    data: "raca",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Tipo",
                    name: "tipo",
                    data: "tipo",
                    searchable: false,
                    orderable: false
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

        $("#pets").on("click", ".btn-delete", function (e) {
            const email = $(e.currentTarget).attr("data-client");
            const pet = $(e.currentTarget).attr("data-pet");

            if (confirm("Você realmente desvincular o pet do cliente?")) {
                $.ajax({
                    url: "{{ route('intern.client.pet.remove', [':cliente', ':pet']) }}".replace(":cliente", email).replace(":pet", pet),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Pet removido", res.message);
                        $("#pets").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao remover", err.responseJSON.message);
                    }
                })
            }
        });

        $(".btn-vincular").on("click", function (e) {
            $.ajax({
                url: "{{ route('intern.client.pet.sync', [$cliente->email, ':pet']) }}".replace(':pet', $("#pet-vinculo").val()),
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Pet vinculado", res.message);
                    $("#pets").DataTable().ajax.reload();
                },
                error: function (err) {
                    showToastr("error", "Erro ao vincular", err.responseJSON.message);
                }
            });
        });
    </script>
@endpush
