@extends("layout.app")

@section("title") | Administração | Tipos de pet @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Tipos de pet cadastrados</h1>

        <section class="my-4">
            <table id="tipos"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.tipo.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar tipo de pet</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#tipos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.tipo.data') }}",
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

        $("#tipos").on("click", ".btn-delete", function (e) {
            const tipo = $(e.currentTarget).attr("data-tipo");

            if (confirm("Você realmente deseja excluir o tipo de pet?")) {
                $.ajax({
                    url: "{{ route('intern.tipo.delete', ':tipo') }}".replace(":tipo", tipo),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Tipo de pet excluído", res.message);
                        $("#tipos").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
