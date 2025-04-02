@extends("layout.app")

@section("title") | Meu painel @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Meu painel</h1>

        <div class="flex flex-col gap-[20px]">
            <a href="{{ route('client.pets.data') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Meus pets</a>
            <a href="{{ route('client.servico.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Meus serviços contratados</a>
            <a href="{{ route('client.servico.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 p-4 cursor-pointer">Agendar serviço</a>
        </div>
    </main>

    <x-footer />
@endsection
