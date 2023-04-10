<?php
    $classes = request()->getUri()->getPath() === route($route)
        ? 'flex items-center p-3 font-bold text-white bg-secondary rounded-lg hover:bg-secondary-hover'
        : 'flex items-center p-3 font-bold text-gray-500 rounded-lg hover:bg-gray-100';
?>

<a href="{{ route($route) }}" class="{{ $classes }}">
    @content
    <span class="ml-3">{{ $name }}</span>
</a>
