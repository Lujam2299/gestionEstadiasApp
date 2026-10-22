<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Estadías · TecNM Ciudad Valles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="estadia-app">
    <a href="#contenido" class="skip-link">Saltar al contenido</a>
    <x-area-navigation />
    <main id="contenido" class="app-content">
        @isset($header)
            <header class="page-heading">{{ $header }}</header>
        @endisset
        {{ $slot }}
    </main>
    <x-footer />
    @livewireScripts
</body>

</html>
