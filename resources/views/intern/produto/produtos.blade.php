@extends("layout.app")

@section("title") | Administração | Produtos @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Produtos cadastrados</h1>

        <section class="my-4">
            <table id="produtos"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.produto.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar produto</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#produtos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.produto.data') }}",
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
                    title: "Descricao",
                    name: "descricao",
                    data: "descricao"
                },
                {
                    title: "Valor",
                    name: "valor",
                    data: "valor"
                },
                {
                    title: "Estoque",
                    name: "estoque",
                    data: "estoque"
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

        $("#produtos").on("click", ".btn-delete", function (e) {
            const produto = $(e.currentTarget).attr("data-produto");

            if (confirm("Você realmente deseja excluir o produto?")) {
                $.ajax({
                    url: "{{ route('intern.produto.delete', ':produto') }}".replace(":produto", produto),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Produto excluído", res.message);
                        $("#produtos").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
