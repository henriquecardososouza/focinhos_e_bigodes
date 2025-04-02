@extends("layout.app")

@section("title") | Administração | Funcionários | Serviços @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Serviços relacionados ao funcionário {{ $funcionario->nome }}</h1>

        <section class="my-4">
            <table id="servicos"></table>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#servicos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.funcionario.servico.data', $funcionario->cpf) }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
            },
            columns: [
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
                    title: "Data",
                    name: "data",
                    data: "data",
                },
                {
                    title: "Estado",
                    name: "estado",
                    data: "estado",
                    searchable: false,
                    orderable: false
                },
                {
                    title: "Agendado",
                    name: "agendado",
                    data: "agendado",
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
