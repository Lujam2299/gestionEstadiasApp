<x-app-layout>
    <x-user-layout>

    </x-user-layout><br>
    <x-username-layout />

    <div class="w-full  py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex flex-wrap justify-center">
                <div class="w-3/4 px-6">
                <div class="card">
                        <div class="card-header text-2xl">
                            Detalles de la Estancia
                        </div>
                        <div class="card-body">
                            <img src="{{ asset('images/tec.jpg') }}" alt="Imagen de la estancia" class="img-fluid">
                            <hr>
                            <h4 class="card-title">Nombre: <b>{{ $estancia->nombre }}</b></h4>
                            <hr>
                            <p class="card-text">Fecha de Convocatoria: {{ \Carbon\Carbon::parse($estancia->fecha_convocatoria)->format('d-m-Y') }}</p>
                            <hr>
                            <p class="card-text">Archivo de Convocatoria:
                                <a class="text-blue-500 underline" href="{{ asset($estancia->archivo_convocatoria) }}" target="_blank">Ver PDF</a>
                            </p>
                        </div>
                        <div class="card-footer">
                        @if ($solicitudesPendientes)
                            <a href="#" class="btn btn-success disabled" disabled>Generar Solicitud</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary float-right">Regresar</a>
                            <div class="text-red-500">No es posible generar solicitud de esta convocatoria, usted ya cuenta con una solicitud realizada, por lo que debe esperar para poder generar una nueva solicitud.</div>
                        @else
                            <a href="{{ route('userCreateSolicitud', $estancia->id) }}" class="btn btn-success">Generar Solicitud</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary float-right">Regresar</a>
                            @endif
                            
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
