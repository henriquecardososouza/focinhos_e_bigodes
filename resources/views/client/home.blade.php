@extends("layout.app")

@section("title") | Meu painel @endsection

@section("content")
    <x-navbar />

    <main class="my-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Bem vindo {{ $user->nome }}</h1>

        <div class="grid gap-[20px] justify-between grid-cols-3">
            <a href="{{ route('client.pets.data') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-8 items-center flex-col flex justify-center cursor-pointer">
                <i class="fa-solid fa-paw text-3xl mb-4"></i>
                Meus pets
            </a>
            <a href="{{ route('client.servico.index') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-8 items-center flex-col flex justify-center cursor-pointer">
                <i class="fa-solid fa-clipboard-check text-3xl mb-4"></i>
                Meus serviços contratados
            </a>
            <a href="{{ route('client.servico.create') }}" class="rounded-xl bg-orange-500 hover:bg-orange-600 transition duration-400 px-4 py-8 items-center flex-col flex justify-center cursor-pointer">
                <i class="fa-solid fa-calendar-days text-3xl mb-4"></i>
                Agendar serviço
            </a>
        </div>
    </main>

    <x-footer />
@endsection
