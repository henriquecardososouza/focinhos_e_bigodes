@extends("layout.app")

@section("title") | Meus Pets | Vacinas @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Vacinas do pet {{ $pet->nome }}</h1>

        <section class="my-4">
            <table id="vacinas"></table>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#vacinas").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('client.pet.vacina.data', $pet->codigo) }}",
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
                    title: "Dose",
                    name: "dose",
                    data: "dose",
                },
                {
                    title: "Data",
                    name: "data",
                    data: "data",
                }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            }
        });
    </script>
@endpush
