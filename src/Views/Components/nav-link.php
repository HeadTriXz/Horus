<?php
    $path = request()->getUri()->getPath();
    $classes = 'flex items-center p-3 font-bold text-gray-500 rounded-lg hover:bg-gray-200';
    if (route($route) === "/") {
        if ($path === "/"){
            $classes = 'flex items-center p-3 font-bold text-white bg-secondary rounded-lg hover:bg-secondary-hover';
        }
    } else if (str_starts_with($path, route($route))) {
        $classes = 'flex items-center p-3 font-bold text-white bg-secondary rounded-lg hover:bg-secondary-hover';
    }
?>

<a href="{{ route($route) }}" class="{{ $classes }}">
    @content()
    <span class="ml-3">{{ $name }}</span>
</a>
