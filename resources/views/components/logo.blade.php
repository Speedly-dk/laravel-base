@props(['class' => 'h-8 w-auto'])

<img src="https://ap3.dk/wp-content/uploads/2025/03/AP3_pos.webp"
     alt="{{ config('app.name', 'Laravel') }}"
     {{ $attributes->merge(['class' => $class]) }}>