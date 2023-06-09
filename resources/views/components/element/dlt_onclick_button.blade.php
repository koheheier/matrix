@props([
    'onclick' => ''
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
            bg-red-500
            hover:bg-red-600
            focus:outline-none
            focus:ring-2
            focus:ring-offset-2
            focus:ring-red-500"
        onclick="{{ $onclick }}"
            >
    {{ $slot }}
</button>