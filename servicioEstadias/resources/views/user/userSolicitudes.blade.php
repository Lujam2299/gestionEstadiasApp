<x-app-layout>
    <x-user-layout>

    </x-user-layout><br>
    <x-username-layout />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                    <div class="min-w-screen py-5 flex items-center justify-center">
                        <div class="bg-white text-gray-600 rounded-lg shadow-xl w-full">
                        @if(session('success'))
                            <div class="bg-green-500 text-white p-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif
                            <div class="overflow-x-auto">
                            <h1 class="flex items-center justify-center text-2xl">Mis solicitudes</h1>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nombre Estancia</th>
                                            <th>Empresa</th>
                                            <th>Fecha de Solicitud</th>
                                            <th>Estado</th>
                                            <th>Observaciones</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($solicitudes as $solicitud)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $solicitud->estancia->nombre }}</td>
                                                <td>{{ $solicitud->empresa}}</td>
                                                <td>{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d-m-Y') }}</td>
                                                <td>
                                                    @if ($solicitud->status == 0)
                                                        @if($solicitud->status_convenio==2)
                                                            Validación de convenio necesaria
                                                        @else
                                                            En revisión
                                                        @endif
                                                    @elseif ($solicitud->status == 1)
                                                        Acciones necesarias
                                                    @elseif ($solicitud->status == 2)
                                                        Aceptado, enviar informes al finalizar estancia
                                                    @elseif ($solicitud->status == 3)
                                                        Rechazado
                                                    @elseif ($solicitud->status == 4)
                                                        Informes enviados, en espera de respuesta
                                                    @elseif ($solicitud->status == 5)
                                                        Observaciones en informes finales, favor de revisar
                                                    @elseif ($solicitud->status == 6)
                                                        Terminado, falta recibir constancia 
                                                    @elseif ($solicitud->status == 7)
                                                        Estancia Terminada   
                                                    @elseif ($solicitud->status == 8)
                                                        Solicitud Cancelada  
                                                    @else
                                                        Estado desconocido
                                                    @endif
                                                </td>
                                                <td>{{$solicitud->observaciones}}</td>
                                                <td>
                                                    @if ($solicitud->status == 0 || $solicitud->status == 1)
                                                        <a class="text-blue-500 underline" href="{{ route('showRequestFiles', $solicitud->id) }}">Editar Solicitud</a><br>
                                                        <div class="action-box">
                                                            <a href="#" class="text-red-500 underline" onclick="confirmCancel({{ $solicitud->id }})">Cancelar Solicitud</a>
                                                        </div>
                                                    @elseif ($solicitud->status ==2)
                                                    <a class="text-blue-500 underline" href="{{route('informesView',['id' => $solicitud->estancia->id, 'sol' => $solicitud->id])}}">Ver Más</a>
                                                    <div class="action-box">
                                                            <a href="#" class="text-red-500 underline" onclick="confirmCancel({{ $solicitud->id }})">Cancelar Solicitud</a>
                                                        </div>
                                                    @elseif ($solicitud->status ==5)
                                                    <a class="text-blue-500 underline" href="{{route('estanciaAccepted')}}">Ver Más</a><br>
                                                    <div class="action-box">
                                                            <a href="#" class="text-red-500 underline" onclick="confirmCancel({{ $solicitud->id }})">Cancelar Solicitud</a>
                                                        </div>
                                                    @elseif ($solicitud->status ==7)
                                                    <a class="text-blue-500 underline"  href="{{ route('descargar-constancia', ['id' => $solicitud->id]) }}">Descargar Constancia</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="flex items-center justify-center w-full">
                                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">Regresar</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
    function confirmCancel(solicitudId) {
        // Confirmación del usuario
        var confirmed = confirm("¿Está seguro que desea cancelar esta solicitud? La acción no podrá deshacerse posteriormente.");
        if (confirmed) {
            // Crear el formulario dinámico
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "/cancelar-solicitud/" + solicitudId;

            // Agregar token CSRF
            var csrfToken = document.createElement("input");
            csrfToken.type = "hidden";
            csrfToken.name = "_token";
            csrfToken.value = "{{ csrf_token() }}";  
            form.appendChild(csrfToken);

            // Agregar campo _method para DELETE
            var methodField = document.createElement("input");
            methodField.type = "hidden";
            methodField.name = "_method";
            methodField.value = "DELETE";
            form.appendChild(methodField);

            // Añadir el formulario al DOM y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
</x-app-layout>
