@props([
    'title' => config('app.name', 'Laravel'),
    'bodyClass' => 'bg-gray-50 dark:bg-gray-900',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="{{ $bodyClass }}">
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.2/dist/flowbite.min.js"></script>
</body>

</html>
