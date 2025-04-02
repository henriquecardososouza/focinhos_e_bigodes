@extends("layout.app")

@section("title") | Administração @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Painel de administração</h1>

        <div class="flex flex-col gap-[20px]">
            <a href="{{ route('intern.client.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar clientes</a>
            <a href="{{ route('intern.funcionario.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar funcionários</a>
            <a href="{{ route('intern.cargo.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar cargos</a>
            <a href="{{ route('intern.pet.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar pets</a>
            <a href="{{ route('intern.vacina.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar vacinas</a>
            <a href="{{ route('intern.servico.contratados.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar serviços contratados</a>
            <a href="{{ route('intern.servico.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar tipos de serviços</a>
            <a href="{{ route('intern.raca.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar raças de pet</a>
            <a href="{{ route('intern.tipo.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar tipos de pet</a>
            <a href="{{ route('intern.produto.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar produtos</a>
            <a href="{{ route('intern.venda.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar vendas</a>
            <a href="{{ route('intern.unidade.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Gerenciar unidades</a>
        </div>
    </main>

    <x-footer />
@endsection
