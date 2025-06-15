@extends("layout.app")

@section('title') | Unidades @endsection

@section('content')
    <x-navbar :original="true" />

    <main class="mt-12">
        <h1 class="font-dosis text-brown-500 text-4xl mb-8 font-bold">Nossas unidades</h1>

        <div>
            @foreach ($unidades as $unidade)
                <div class="rounded-xl border p-8 my-4">
                    <h1 class="font-red-hat text-2xl mb-4 font-bold">Unidade {{ $unidade->dadosEndereco->bairro }}</h1>
                    <dl class="flex gap-[20px]">
                        <dt class="font-bold w-[100px]">Gerente: </dt>
                        <dd>{{ $unidade->dadosGerente ? $unidade->dadosGerente->nome : "---" }}</dd>
                    </dl>
                    <dl class="flex gap-[20px]">
                        <dt class="font-bold w-[100px]">Rua: </dt>
                        <dd>{{ $unidade->dadosEndereco->rua }}</dd>
                    </dl>
                    <dl class="flex gap-[20px]">
                        <dt class="font-bold w-[100px]">Número: </dt>
                        <dd>{{ $unidade->dadosEndereco->numero }}</dd>
                    </dl>
                    <dl class="flex gap-[20px]">
                        <dt class="font-bold w-[100px]">Bairro: </dt>
                        <dd>{{ $unidade->dadosEndereco->bairro }}</dd>
                    </dl>
                    <dl class="flex gap-[20px]">
                        <dt class="font-bold w-[100px]">Cidade: </dt>
                        <dd>{{ $unidade->dadosEndereco->cidade }}</dd>
                    </dl>
                </div>
            @endforeach
        </div>
    </main>

    <x-footer />
@endsection
