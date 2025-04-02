@extends("layout.app")

@section('content')
    <x-navbar :original="true" />
    <main>
        <section id="title" class="my-2 relative flex flex-col aspect-[2/1] justify-center">
            <div class="w-[50%] aspect-square top-0 right-0 absolute z-[-1]">
                <div class="relative w-full h-full">
                    <svg class="absolute top-0 right-0 z-[-2] w-full h-full" viewBox="0 0 100 100">
                        <circle r="75" cx="75" cy="50" class="fill-orange-500"></circle>
                    </svg>

                    <div class="absolute bottom-0 right-0 z-[-1] w-[80%] overflow-hidden">
                        <img class="transform-[translateY(20px)]" src="{{ Vite::asset('resources/img/display/landing.png') }}" alt="">
                    </div>
                </div>
            </div>

            <h1 class="font-dosis text-brown-500 text-6xl font-bold">Focinhos & Bigodes</h1>
            <p class="py-7 text-gray-900">Cuide do seu pet com amor e carinho!<br>Produtos, banho, tosa e tudo o que seu amigo precisa em um só lugar!</p>
            <a href="#services" class="py-2 px-4 bg-orange-500 text-gray-800 rounded-xl w-[max-content] cursor-pointer hover:bg-orange-600 transition duration-400">Conheça nossos serviços</a>
        </section>

        <section id="products" class="my-12">
            <h1 class="font-bold text-4xl my-4 font-dosis text-brown-500">Produtos</h1>

            <div class="overflow-x-auto">
                <div class="flex justify-start align-center my-12 gap-[20px] px-2 font-dosis w-[max-content]">
                    @foreach (\App\Models\Produto::all() as $produto)
                        <div class="w-[200px] h-[200px] perspective-normal cursor-pointer" x-data="{ isFlipped: false }" @mouseenter="isFlipped = true" @mouseleave="isFlipped = false">
                            <div class="w-full h-full transition duration-500 transform-3d shadow-[0_0_10px_#000000AA] rounded-xl" :class="{ 'transform-[rotateY(180deg)]': isFlipped, 'bg-orange-500': isFlipped, 'text-gray-800': isFlipped }">
                                <div class="rounded-xl absolute font-red-hat top-0 right-0 w-full h-full backface-hidden flex flex-col items-center justify-center text-2xl">
                                    <i class="fa-solid fa-bottle-water mb-4"></i>
                                    <span class="font-bold text-center">{{ $produto->nome }}</span>
                                </div>

                                <div class="absolute p-2 text-center text-xl top-0 right-0 w-full h-full backface-hidden flex flex-col items-center justify-center transform-[rotateY(180deg)]">
                                    <small>{{ $produto->descricao }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="services" class="my-12">
            <h1 class="font-bold text-4xl my-4 font-dosis text-brown-500">Serviços</h1>

            <div class="overflow-x-auto">
                <div class="flex justify-start align-center my-12 gap-[20px] px-2 font-dosis w-[max-content]">
                    @foreach (\App\Models\Servico::all() as $servico)
                        <div class="w-[200px] h-[200px] perspective-normal cursor-pointer" x-data="{ isFlipped: false }" @mouseenter="isFlipped = true" @mouseleave="isFlipped = false">
                            <div class="w-full h-full transition duration-500 transform-3d shadow-[0_0_10px_#000000AA] rounded-xl" :class="{ 'transform-[rotateY(180deg)]': isFlipped, 'bg-orange-500': isFlipped, 'text-gray-800': isFlipped }">
                                <div class="rounded-xl absolute font-red-hat top-0 right-0 w-full h-full backface-hidden flex flex-col items-center justify-center text-2xl">
                                    <i class="fa-solid fa-shower mb-4"></i>
                                    <span class="font-bold text-center">{{ $servico->tipo }}</span>
                                </div>

                                <div class="absolute p-2 text-center text-xl top-0 right-0 w-full h-full backface-hidden flex flex-col items-center justify-center transform-[rotateY(180deg)]">
                                    <small>{{ $servico->descricao }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="about" class="my-12">
            <h1 class="font-bold text-4xl my-4 font-dosis text-brown-500">Sobre nós</h1>

            <p class="my-4 text-justify">
                Em 2010, nasceu o Focinhos & Bigodes PetShop, um sonho transformado em realidade por Lucas Mendes, um apaixonado por animais que sempre acreditou que todo pet merece cuidado, carinho e qualidade de vida. O pequeno estabelecimento começou em um bairro aconchegante da cidade, oferecendo serviços básicos como banho e tosa, mas logo se tornou referência no segmento graças ao atendimento acolhedor e ao compromisso com o bem-estar dos pets.
            </p>
            <p class="my-4 text-justify">
                Com o passar dos anos, a empresa cresceu e expandiu seus serviços, sempre sob a liderança do nosso dedicado chefe de operações, Mariana Silva, que trouxe inovação e aprimorou nossos processos. Hoje, além de banho e tosa, contamos com atendimento veterinário, vacinação, pet walk e uma loja completa com produtos de alta qualidade para cães, gatos e outros animais de estimação.
            </p>
            <p class="my-4 text-justify">
                Nossa missão é simples, mas essencial: proporcionar saúde, conforto e felicidade para os pets, garantindo a tranquilidade de seus tutores. Acreditamos que cada animal é único e merece um tratamento especial, por isso trabalhamos com profissionais capacitados e utilizamos apenas produtos seguros e de qualidade.
            </p>
            <p class="my-4 text-justify">
                Os valores que guiam o Focinhos & Bigodes PetShop são amor pelos animais, respeito, responsabilidade e compromisso com a excelência. Cada pet que entra por nossas portas é tratado como parte da família, e cada cliente encontra em nós um parceiro de confiança para cuidar do seu melhor amigo. Seguimos evoluindo, sempre prontos para oferecer o melhor para quem nos escolhe!
            </p>
        </section>
    </main>

    <x-footer />
@endsection
