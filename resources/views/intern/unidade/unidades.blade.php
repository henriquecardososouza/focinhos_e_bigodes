@extends("layout.app")

@section("title") | Administração | Unidades @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Unidades cadastrados</h1>

        <section class="my-4">
            <table id="unidades"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.unidade.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar unidade</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#unidades").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.unidade.data') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
                {
                    title: "Bairro",
                    name: "bairro",
                    data: "bairro"
                },
                {
                    title: "Gerente",
                    name: "gerente",
                    data: "gerente"
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

        $("#unidades").on("click", ".btn-delete", function (e) {
            const unidade = $(e.currentTarget).attr("data-unidade");

            if (confirm("Você realmente deseja excluir a unidade?")) {
                $.ajax({
                    url: "{{ route('intern.unidade.delete', ':unidade') }}".replace(":unidade", unidade),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Unidade excluída", res.message);
                        $("#unidades").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
