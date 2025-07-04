<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>讓兄弟組 - {{ $page_name }} </title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @if (isset($show_header) && $show_header)
        <x-header title="讓兄弟組 - 打造你的夢想電腦" />
    @endif
    <main>
        @yield('content')
    </main>
    @if (isset($show_footer) && $show_footer)
        @include('includes.footer')
    @endif
</body>

</html>
