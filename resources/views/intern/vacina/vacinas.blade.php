@extends("layout.app")

@section("title") | Administração | Vacinas @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Vacinas cadastradas</h1>

        <section class="my-4">
            <table id="vacinas"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.vacina.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar vacina</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#vacinas").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.vacina.data') }}",
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
                    title: "Descrição",
                    name: "descricao",
                    data: "descricao"
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
            const email = $(e.currentTarget).attr("data-vacina");

            if (confirm("Você realmente deseja excluir a vacina?")) {
                $.ajax({
                    url: "{{ route('intern.vacina.delete', ':vacina') }}".replace(":vacina", email),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Vacina excluída", res.message);
                        $("#vacinas").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
