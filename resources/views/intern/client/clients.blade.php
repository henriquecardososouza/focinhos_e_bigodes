@extends("layout.app")

@section("title") | Administração | Clientes @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Clientes cadastrados</h1>

        <section class="my-4">
            <table id="clients"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.client.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar cliente</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#clients").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.client.data') }}",
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
                    title: "Endereço",
                    name: "endereco",
                    data: "endereco",
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

        $("#clients").on("click", ".btn-delete", function (e) {
            const email = $(e.currentTarget).attr("data-client");

            if (confirm("Você realmente deseja excluir o cliente?")) {
                $.ajax({
                    url: "{{ route('intern.client.delete', ':cliente') }}".replace(":cliente", email),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Cliente excluído", res.message);
                        $("#clients").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
