@extends("layout.app")

@section("title") | Meus Pets @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Meus pets</h1>

        <section class="my-4">
            <table id="pets"></table>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#pets").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('client.pets.data') }}",
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
    </script>
@endpush
