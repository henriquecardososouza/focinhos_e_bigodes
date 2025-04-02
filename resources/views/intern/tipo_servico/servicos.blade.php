@extends("layout.app")

@section("title") | Administração | Serviços @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Tipos de serviço cadastrados</h1>

        <section class="my-4">
            <table id="tipos"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.servico.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar tipo de serviço</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#tipos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.servico.data') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
                {
                    title: "Tipo",
                    name: "tipo",
                    data: "tipo"
                },
                {
                    title: "Descrição",
                    name: "descricao",
                    data: "descricao"
                },
                {
                    title: "Valor",
                    name: "valor",
                    data: "valor"
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
            const servico = $(e.currentTarget).attr("data-servico");

            if (confirm("Você realmente deseja excluir o tipo de serviço?")) {
                $.ajax({
                    url: "{{ route('intern.servico.delete', ':servico') }}".replace(":servico", servico),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Tipo de serviço excluído", res.message);
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
