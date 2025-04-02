<footer class="grid bg-orange-500 p-4 place-items-center">
    <img class="my-5" width="300" src="{{ Vite::asset('resources/img/brand.png') }}" alt="{{ env("APP_NAME") }}">
    <div class="w-[fit-content] flex flex-col text-gray-800 text-center">
        <div class="flex justify-between my-2 gap-[20px]">
            <a href="#" class="flex items-center gap-[5px]">
                <i class="fa-brands fa-instagram"></i>
                <span>Instagram</span>
            </a>

            <a href="#" class="flex items-center gap-[5px]">
                <i class="fa-brands fa-facebook"></i>
                <span>Facebook</span>
            </a>

            <a href="#" class="flex items-center gap-[5px]">
                <i class="fa-brands fa-whatsapp"></i>
                <span>(38) 98402-2718</span>
            </a>
        </div>

        <span>&copy; {{ env("APP_NAME") }} - {{ now()->format('Y') }}</span>
    </div>
</footer>
