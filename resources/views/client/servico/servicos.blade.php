@extends("layout.app")

@section("title") | Meus Serviços @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Meus serviços contratados</h1>

        <section class="my-4">
            <table id="servicos"></table>
        </section>

        <section class="my-6 flex justify-end">
            <a href="{{ route('client.servico.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Agendar serviço</a>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#servicos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('client.servico.data') }}",
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
                }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            }
        });
    </script>
@endpush
