@extends("layout.app")

@section("title") | Administração | Raças @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Raças de pet cadastradas</h1>

        <section class="my-4">
            <table id="racas"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.raca.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar raça</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#racas").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.raca.data') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
                {
                    title: "Nome",
                    name: "nome",
                    data: "nome"
                },
                {
                    title: "Tipo",
                    name: "tipo",
                    data: "tipo"
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

        $("#racas").on("click", ".btn-delete", function (e) {
            const raca = $(e.currentTarget).attr("data-raca");

            if (confirm("Você realmente deseja excluir a raça?")) {
                $.ajax({
                    url: "{{ route('intern.raca.delete', ':raca') }}".replace(":raca", raca),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Raça excluída", res.message);
                        $("#racas").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
