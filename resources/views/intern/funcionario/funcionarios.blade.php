@extends("layout.app")

@section("title") | Administração | Funcionários @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Funcionários cadastrados</h1>

        <section class="my-4">
            <table id="funcionarios"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.funcionario.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar funcionário</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#funcionarios").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.funcionario.data') }}",
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
                    title: "Email",
                    name: "email",
                    data: "email",
                },
                {
                    title: "Telefone",
                    name: "telefone",
                    data: "telefone",
                },
                {
                    title: "Cargo",
                    name: "cargo",
                    data: "cargo",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Unidade",
                    name: "unidade",
                    data: "unidade",
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

        $("#funcionarios").on("click", ".btn-delete", function (e) {
            const email = $(e.currentTarget).attr("data-funcionario");

            if (confirm("Você realmente deseja excluir o funcionário?")) {
                $.ajax({
                    url: "{{ route('intern.funcionario.delete', ':funcionario') }}".replace(":funcionario", email),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Funcionário excluído", res.message);
                        $("#funcionarios").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
