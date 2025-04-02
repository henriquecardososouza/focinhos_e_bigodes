@props(['id'])

<div id="{{ $id }}" class="fixed top-0 left-0 flex items-center justify-center w-full h-full bg-black/75" x-show="open"
     x-transition:enter="transition-opacity duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="max-h-[600px] overflow-auto p-4 text-left bg-orange-500 rounded-2xl shadow md:p-6 lg:p-8 text-gray-800 w-11/12 md:min-w-[450px] max-w-[400px]" @click.away="open = false"
         x-transition:enter="transition-transform duration-300"
         x-transition:enter-start="scale-90 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         x-transition:leave="transition-transform duration-300"
         x-transition:leave-start="scale-100 opacity-100"
         x-transition:leave-end="scale-90 opacity-0">
        {{$slot}}
    </div>
</div>

