@extends("layout.app")

@section("title") | Administração | Vendas | Produtos @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Produtos da venda {{ $venda->codigo }}</h1>

        <section class="my-4">
            <table id="produtos"></table>
        </section>

        <section class="my-6 flex justify-end" x-data="{ open: false }">
            <button @click="open=true" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Adicionar produtos a venda</button>
            <x-modal :id="'modal-vincular'">
                <div class="flex justify-between text-brown-500">
                    <h1 class="font-dosis text-2xl font-bold">Adicionar produto à venda</h1>
                    <button @click="open=false" class="cursor-pointer hover:text-gray-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <label for="produto-vinculo" class="mt-8 block">Produto | Estoque</label>
                <select class="w-full p-2 border rounded-xl bg-white mb-8" id="produto-vinculo">
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->codigo }}">{{ $produto->nome }} | {{ $produto->estoque }}</option>
                    @endforeach
                </select>

                <label for="quantidade">Quantidade</label>
                <input class="w-full p-2 border rounded-xl bg-white mb-8" type="number" id="quantidade">

                <div class="flex justify-end">
                    <button data-venda="{{ $venda->codigo }}" class="btn-vincular rounded-xl max-w-[min-content] bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-3 cursor-pointer">Adicionar</button>
                </div>
            </x-modal>
        </section>
    </main>

    <x-footer />
@endsection

@push('script')
    <script>
        $("#produtos").DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('intern.venda.produto.data', $venda->codigo) }}",
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
                    title: "Descrição",
                    name: "descricao",
                    data: "descricao",
                },
                {
                    title: "Quantidade",
                    name: "quantidade",
                    data: "quantidade",
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

        $("#produtos").on("click", ".btn-delete", function (e) {
            const venda = $(e.currentTarget).attr("data-venda");
            const produto = $(e.currentTarget).attr("data-produto");

            if (confirm("Você realmente deseja remover o produto da venda?")) {
                $.ajax({
                    url: "{{ route('intern.venda.produto.remove', [':venda', ':produto']) }}".replace(":venda", venda).replace(":produto", produto),
                    method: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        showToastr("success", "Produto removido", res.message);
                        $("#produtos").DataTable().ajax.reload();
                    },
                    error: function (err) {
                        showToastr("error", "Erro ao remover", err.responseJSON.message);
                    }
                })
            }
        });

        $(".btn-vincular").on("click", function (e) {
            $.ajax({
                url: "{{ route('intern.venda.produto.sync', [$venda->codigo, ':produto']) }}".replace(':produto', $("#produto-vinculo").val()),
                method: 'POST',
                data: {
                    quantidade: $("#quantidade").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    showToastr("success", "Produto adicionado", res.message);
                    $("#produtos").DataTable().ajax.reload();
                },
                error: function (err) {
                    showToastr("error", "Erro ao vincular", err.responseJSON.message);
                }
            });
        });
    </script>
@endpush
