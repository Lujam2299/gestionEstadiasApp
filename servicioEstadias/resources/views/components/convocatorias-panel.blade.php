@props(['estancias', 'admin' => false])
@php
    $activas = $estancias->where('vigente', 0);
    $proximas = $activas->filter(
        fn($item) => $item->fecha_cierre >= today()->toDateString() &&
            $item->fecha_cierre <= today()->addDays(7)->toDateString(),
    );
@endphp
<div class="workspace">
    <section class="welcome-banner">
        <div><span class="eyebrow">{{ $admin ? 'DESARROLLO ACADÉMICO' : 'CRECE, CONECTA, COMPARTE' }}</span>
            <h1>{{ $admin ? 'Impulsa nuevas' : 'Tu próxima' }}<br><em>{{ $admin ? 'experiencias.' : 'experiencia.' }}</em>
            </h1>
            <p>{{ $admin ? 'Administra las oportunidades que conectan a nuestra comunidad docente con el mundo profesional.' : 'Descubre oportunidades para llevar tu conocimiento más lejos y conectar con el sector productivo.' }}
            </p>
            <a class="button-primary"
                href="{{ $admin ? route('crearEstancia') : '#convocatorias' }}">{{ $admin ? 'Crear convocatoria' : 'Explorar convocatorias' }}
                <span aria-hidden="true">↗</span></a>
        </div>
        <div class="hero-visual" aria-hidden="true">
            <div class="hero-ring"></div><span class="hero-number">e.</span><span class="hero-caption">APRENDER<br>PARA
                TRANSFORMAR</span>
        </div>
    </section>
    <section class="metric-strip" aria-label="Resumen de convocatorias">
        <div><span class="metric-icon"
                aria-hidden="true">↗</span><span><strong>{{ $activas->count() }}</strong><small>Convocatorias
                    disponibles</small></span></div>
        <div><span class="metric-icon warm"
                aria-hidden="true">◷</span><span><strong>{{ $proximas->count() }}</strong><small>Cierran en los próximos
                    7 días</small></span></div>
        <div class="metric-note"><span class="status-dot"></span><span>Tu experiencia hace<br><strong>la
                    diferencia.</strong></span></div>
    </section>
    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    <div class="workspace-columns">
        <section id="convocatorias" class="opportunities-section">
            <div class="section-heading">
                <div><span class="eyebrow">OPORTUNIDADES</span>
                    <h2>Convocatorias abiertas</h2>
                </div><span class="count-label">{{ $activas->count() }} disponibles</span>
            </div>
            <div class="opportunity-grid">
                @forelse ($activas as $estancia)
                    @php $abierta = $estancia->fecha_convocatoria <= today()->toDateString() && $estancia->fecha_cierre >= today()->toDateString(); @endphp
                    <article class="opportunity-card">
                        <div class="opportunity-top"><span class="opportunity-monogram"
                                aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span
                                class="badge-status {{ $abierta ? 'positive' : 'neutral' }}">{{ $abierta ? 'Abierta' : 'Programada' }}</span>
                        </div>
                        <p class="institution-label">{{ $estancia->empresa }}</p>
                        <h3>{{ $estancia->nombre }}</h3>
                        <dl class="opportunity-dates">
                            <div>
                                <dt>Apertura</dt>
                                <dd>{{ \Carbon\Carbon::parse($estancia->fecha_convocatoria)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt>Cierre</dt>
                                <dd>{{ \Carbon\Carbon::parse($estancia->fecha_cierre)->format('d/m/Y') }}</dd>
                            </div>
                        </dl>
                        <div class="opportunity-actions"><a class="text-action"
                                href="{{ route($admin ? 'verEstancia' : 'showUserEstancia', $estancia->id) }}">Ver
                                convocatoria <span aria-hidden="true">↗</span></a>
                            @if ($admin)
                                <form method="POST" action="{{ route('eliminar-estancia', $estancia->id) }}">@csrf
                                    @method('DELETE')<button class="quiet-action" type="submit">Cerrar</button></form>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="empty-state"><span aria-hidden="true">↗</span>
                        <h3>Lo que sigue, empieza pronto.</h3>
                        <p>Aún no hay convocatorias abiertas.
                            {{ $admin ? 'Publica una nueva oportunidad para la comunidad.' : 'Vuelve más adelante para conocer nuevas oportunidades.' }}
                        </p>
                        @if ($admin)
                            <a class="button-primary" href="{{ route('crearEstancia') }}">Crear convocatoria</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>
        <aside class="journey-panel"><span
                class="eyebrow">{{ $admin ? 'GESTIÓN ACADÉMICA' : 'ASÍ COMIENZA TU ESTADÍA' }}</span>
            <h2>{{ $admin ? 'Todo, en un mismo lugar.' : 'De la idea a la experiencia.' }}</h2>
            <ol>
                <li><span>01</span>
                    <div><strong>{{ $admin ? 'Publica una oportunidad' : 'Encuentra tu oportunidad' }}</strong>
                        <p>{{ $admin ? 'Define fechas y documentos de cada convocatoria.' : 'Consulta las convocatorias y sus requisitos.' }}
                        </p>
                    </div>
                </li>
                <li><span>02</span>
                    <div><strong>{{ $admin ? 'Acompaña las solicitudes' : 'Prepara tu solicitud' }}</strong>
                        <p>{{ $admin ? 'Revisa documentos y comparte observaciones.' : 'Completa tu información y adjunta tus documentos.' }}
                        </p>
                    </div>
                </li>
                <li><span>03</span>
                    <div><strong>{{ $admin ? 'Cierra el ciclo' : 'Comparte lo aprendido' }}</strong>
                        <p>{{ $admin ? 'Valida informes y entrega las constancias.' : 'Envía tus informes y recibe tu constancia.' }}
                        </p>
                    </div>
                </li>
            </ol>
            <a class="text-action"
                href="{{ route($admin ? 'solicitudes' : 'userSolicitudes') }}">{{ $admin ? 'Revisar solicitudes' : 'Ver mis solicitudes' }}
                <span aria-hidden="true">→</span></a>
        </aside>
    </div>
</div>
