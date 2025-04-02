@extends("layout.app")

@section("title") | Administração | Vendas @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Vendas cadastradas</h1>

        <section class="my-4">
            <table id="vendas"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.venda.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Registrar venda</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#vendas").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.venda.data') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
                {
                    title: "Código",
                    name: "codigo",
                    data: "codigo",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Cliente",
                    name: "cliente",
                    data: "cliente"
                },
                {
                    title: "Funcionário",
                    name: "funcionario",
                    data: "funcionario"
                },
                {
                    title: "Valor",
                    name: "valor",
                    data: "valor"
                },
                {
                    title: "Data",
                    name: "data",
                    data: "data",
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

        $("#vendas").on("click", ".btn-delete", function (e) {
            const venda = $(e.currentTarget).attr("data-venda");

            if (confirm("Você realmente deseja excluir a venda?")) {
                $.ajax({
                    url: "{{ route('intern.venda.delete', ':venda') }}".replace(":venda", venda),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Venda excluída", res.message);
                        $("#vendas").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
