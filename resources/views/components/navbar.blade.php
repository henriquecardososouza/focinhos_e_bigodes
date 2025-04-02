@props(["original" => false])

@if (\Illuminate\Support\Facades\Auth::check() && !$original)
    @if (\App\Models\Funcionario::where("credencial", "LIKE", \Illuminate\Support\Facades\Auth::id())->count() > 0)
        <nav class="flex px-4 items-center relative py-1">
            <a href="{{ route('landing') }}" class="text-lg font-bold md:py-0 py-4">
                <img class="rounded-2xl" src="{{ Vite::asset('resources/img/icon.png') }}" alt="{{ env('APP_NAME') }}" width="70">
            </a>
            <ul class="md:px-2 ml-auto md:flex md:space-x-2 absolute md:relative top-full left-0 right-0">
                <li>
                    <a href="{{ route('intern.home') }}" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Painel</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('intern.servico.contratados.index') }}" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Serviços contratados</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="flex transition duration-400 gap-[10px] text-gray-800 md:inline-flex px-4 py-2 bg-orange-500 rounded-xl items-center hover:bg-orange-600">
                        <span>Sair</span>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </li>
            </ul>
        </nav>
    @else
        <nav class="flex px-4 items-center relative py-1">
            <a href="{{ route('landing') }}" class="text-lg font-bold md:py-0 py-4">
                <img class="rounded-2xl" src="{{ Vite::asset('resources/img/icon.png') }}" alt="{{ env('APP_NAME') }}" width="70">
            </a>
            <ul class="md:px-2 ml-auto md:flex md:space-x-2 absolute md:relative top-full left-0 right-0">
                <li>
                    <a href="{{ route('client.home') }}" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Minha conta</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('units') }}" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Nossas unidades</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('landing') }}#products" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Produtos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('landing') }}#services" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Serviços</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('landing') }}#about" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                        <span>Conheça-nos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="flex transition duration-400 gap-[10px] text-gray-800 md:inline-flex px-4 py-2 bg-orange-500 rounded-xl items-center hover:bg-orange-600">
                        <span>Sair</span>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
@else
    <nav class="flex px-4 items-center relative py-1">
        <a href="{{ route('landing') }}" class="text-lg font-bold md:py-0 py-4">
            <img class="rounded-2xl" src="{{ Vite::asset('resources/img/icon.png') }}" alt="{{ env('APP_NAME') }}" width="70">
        </a>
        <ul class="md:px-2 ml-auto md:flex md:space-x-2 absolute md:relative top-full left-0 right-0">
            <li>
                <a href="{{ route('units') }}" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                    <span>Nossas unidades</span>
                </a>
            </li>
            <li>
                <a href="{{ route('landing') }}#products" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                    <span>Produtos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('landing') }}#services" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                    <span>Serviços</span>
                </a>
            </li>
            <li>
                <a href="{{ route('landing') }}#about" class="flex md:inline-flex px-4 py-2 items-center hover:bg-gray-200 rounded-xl transition duration-400">
                    <span>Conheça-nos</span>
                </a>
            </li>

            @if (!Auth::check())
                <li>
                    <a href="{{ route('login.index') }}" class="flex transition duration-400 gap-[10px] text-gray-800 md:inline-flex px-4 py-2 bg-orange-500 rounded-xl items-center hover:bg-orange-600">
                        <span>Entrar</span>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </li>
            @elseif (\App\Models\Funcionario::where("credencial", "LIKE", \Illuminate\Support\Facades\Auth::id())->count() > 0)
                <li>
                    <a href="{{ route('intern.home') }}" class="flex transition duration-400 gap-[10px] text-gray-800 md:inline-flex px-4 py-2 bg-orange-500 rounded-xl items-center hover:bg-orange-600">
                        <span>Painel</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('client.home') }}" class="flex transition duration-400 gap-[10px] text-gray-800 md:inline-flex px-4 py-2 bg-orange-500 rounded-xl items-center hover:bg-orange-600">
                        <span>Minha conta</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
