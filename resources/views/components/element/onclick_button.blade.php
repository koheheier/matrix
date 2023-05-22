@props([
    'onclick' => '',
    'color' => 'blue'
])
<button type="button" 
        class="inline-flex 
            justify-center
            py-2
            px-4
            border
            border-transparent
            shadow-sm
            text-sm
            font-medium
            rounded-md
            text-white
            bg-{{ $color }}-500
            hover:bg-{{ $color }}-600
            focus:outline-none
            focus:ring-2
            focus:ring-offset-2
            focus:ring-{{ $color }}-500"
        onclick="{{ $onclick }}"
            >
    {{ $slot }}
</button>