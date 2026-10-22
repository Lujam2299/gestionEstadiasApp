@php
    $email = auth()->user()?->email;
    $area = match ($email) {
        'desarrolloacademico@tecvalles.mx' => 'Desarrollo académico',
        'vinculacion@tecvalles.mx' => 'Vinculación',
        default => 'Portal docente',
    };
    $links = match ($email) {
        'desarrolloacademico@tecvalles.mx' => [
            ['adminDashboard', 'Convocatorias'],
            ['crearEstancia', 'Nueva convocatoria'],
            ['solicitudes', 'Solicitudes'],
            ['showInformes', 'Informes'],
            ['historico-solicitudes', 'Histórico de solicitudes'],
            ['historico-convocatorias', 'Histórico de convocatorias'],
            ['showUsers', 'Docentes'],
        ],
        'vinculacion@tecvalles.mx' => [['vinculacionDashboard', 'Solicitudes'], ['showConvenios', 'Convenios']],
        default => [
            ['dashboard', 'Convocatorias'],
            ['userSolicitudes', 'Mis solicitudes'],
            ['estanciaAccepted', 'Mis informes'],
        ],
    };
@endphp
<header class="site-header">
    <div class="brand-row">
        <a class="brand" href="{{ route($links[0][0]) }}" aria-label="Estadías, inicio">
            <span class="brand-symbol" aria-hidden="true">e<span>.</span></span>
            <span><strong>Estadías</strong><small>TecNM · Ciudad Valles</small></span>
        </a>
        <span class="area-label">{{ $area }}</span>
        <div class="account-actions">
            <a href="{{ route('profile.edit') }}" class="account-link"><span
                    class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span><span
                    class="account-name">{{ auth()->user()->name }}</span></a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-link" type="submit">Salir
                    <span aria-hidden="true">↗</span></button></form>
        </div>
    </div>
    <nav class="workspace-nav" aria-label="Navegación principal">
        @foreach ($links as [$route, $label])
            <a href="{{ route($route) }}" @class(['workspace-link', 'is-active' => request()->routeIs($route)])
                @if (request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
</header>
