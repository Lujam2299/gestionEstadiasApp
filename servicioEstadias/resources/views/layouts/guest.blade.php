<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acceso · Estadías TecNM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="estadia-app guest-page">
    <main class="access-layout">
        <section class="access-story" aria-label="Portal de estadías">
            <a href="{{ url('/') }}" class="brand"><span
                    class="brand-symbol">e<span>.</span></span><span><strong>Estadías</strong><small>TecNM · Ciudad
                        Valles</small></span></a>
            <div class="access-copy"><span class="eyebrow light">VINCULACIÓN QUE TRANSFORMA</span>
                <h1>El conocimiento<br>se vive <em>afuera.</em></h1>
                <p>Conecta tu experiencia docente con nuevas oportunidades. Un solo espacio para tus convocatorias,
                    solicitudes e informes.</p>
            </div>
            <div class="access-bottom"><span>Academia + experiencia</span><span aria-hidden="true">↗</span></div>
            <div class="access-orbit" aria-hidden="true"></div>
        </section>
        <section class="access-form-panel">
            <div class="access-form"><span class="eyebrow">PORTAL INSTITUCIONAL</span>{{ $slot }}</div>
            <p class="access-help">Instituto Tecnológico de Ciudad Valles<br><span>Desarrollo académico · Vinculación ·
                    Docentes</span></p>
        </section>
    </main>
</body>

</html>
