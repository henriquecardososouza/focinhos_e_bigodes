@extends("layout.app")

@section("title") | Administração | Pets @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Pets cadastrados</h1>

        <section class="my-4">
            <table id="pets"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('intern.pet.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Cadastrar pet</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#pets").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.pet.data') }}",
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
                    title: "Data nascimento",
                    name: "data_nasc",
                    data: "data_nasc",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Raça",
                    name: "raca",
                    data: "raca",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Tipo",
                    name: "tipo",
                    data: "tipo",
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

        $("#pets").on("click", ".btn-delete", function (e) {
            const email = $(e.currentTarget).attr("data-pet");

            if (confirm("Você realmente deseja excluir o pet?")) {
                $.ajax({
                    url: "{{ route('intern.pet.delete', ':pet') }}".replace(":pet", email),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Pet excluído", res.message);
                        $("#pets").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao excluir", err.responseJSON.message);
                    }
                })
            }
        });
    </script>
@endpush
