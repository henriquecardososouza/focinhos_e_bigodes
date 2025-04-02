@extends("layout.app")

@section("title") | Administração | Serviços @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Serviços cadastrados</h1>

        <section class="my-4">
            <table id="servicos"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.servico.contratados.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Registrar serviço</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#servicos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.servico.contratados.data') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
                {
                    title: "Serviço",
                    name: "servico",
                    data: "servico",
                },
                {
                    title: "Cliente",
                    name: "cliente",
                    data: "cliente",
                },
                {
                    title: "Pet",
                    name: "pet",
                    data: "pet",
                },
                {
                    title: "Prestador",
                    name: "funcionario",
                    data: "funcionario",
                },
                {
                    title: "Agendado",
                    name: "agendado",
                    data: "agendado",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Estado",
                    name: "estado",
                    data: "estado",
                    searchable: false,
                    orderable: false
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

        $("#servicos").on("click", ".btn-complete", function (e) {
            const servico = $(e.currentTarget).attr("data-servico");

            if (confirm("Você realmente deseja marcar o serviço como concluído?")) {
                $.ajax({
                    url: "{{ route("intern.servico.contratados.complete", ":servico") }}".replace(":servico", servico),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Serviço concluído", res.message);
                        $("#servicos").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao concluir", err.responseJSON.message);
                    }
                })
            }
        });

        $("#servicos").on("click", ".btn-delete", function (e) {
            const servico = $(e.currentTarget).attr("data-servico");

            if (confirm("Você realmente deseja excluir o serviço?")) {
                $.ajax({
                    url: "{{ route('intern.servico.contratados.delete', ':servico') }}".replace(":servico", servico),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Serviço excluído", res.message);
                        $("#servicos").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
