@extends("layout.app")

@section("title") | Administração | Cargos @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Cargos cadastrados</h1>

        <section class="my-4">
            <table id="cargos"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.cargo.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar cargo</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#cargos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.cargo.data') }}",
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
                    title: "Salário",
                    name: "salario",
                    data: "salario"
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

        $("#cargos").on("click", ".btn-delete", function (e) {
            const cargo = $(e.currentTarget).attr("data-cargo");

            if (confirm("Você realmente deseja excluir o cargo?")) {
                $.ajax({
                    url: "{{ route('intern.cargo.delete', ':cargo') }}".replace(":cargo", cargo),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Cargo excluído", res.message);
                        $("#cargos").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
