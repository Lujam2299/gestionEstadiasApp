<?php
use Carbon\Carbon;
?>
<x-app-layout>
    <x-admin-layout><br>
    <x-username-layout />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <div class="min-w-screen py-5 flex items-center justify-center">
                    <div class="bg-white text-gray-600 rounded-lg shadow-xl w-full">
                        <div class="overflow-x-auto">
                                <h1 class="flex items-center justify-center text-3xl">Solicitudes Recientes</h1>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Docente</th>
                                            <th>Estancia</th>
                                            <th>Email</th>
                                            <th>Empresa</th>
                                            <th>Status</th>
                                            <th>Fecha de Solicitud</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($currentRequests as $request)
                                            @if($request->status==2)
                                            @elseif($request->status==3)
                                            @elseif($request->status==4)
                                            @elseif($request->status==5)
                                            @elseif($request->status==7)
                                            @elseif($request->status==8)
                                            @elseif($request->status_convenio==1)
                                            @else
                                            <tr>
                                                <td>{{ $request->docente }}</td>
                                                <td>{{ $request->estancia->nombre }}</td>
                                                <td>{{ $request->email }}</td>
                                                <td>{{ $request->empresa }}</td>
                                                <td>
                                                    @if($request->status==0)
                                                        En revisión
                                                    @elseif($request->status==1)
                                                        Observaciones realizadas, en espera de respuesta
                                                    @elseif($request->status==6)
                                                        Terminado, falta enviar Constancia    
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($request->fecha_solicitud)->format('d-m-Y') }}</td>
                                                <td>
                                                    @if($request->status==6)
                                                    <a href="{{ route('informes.showUploadForm', $request->id) }}" class="text-blue-500 hover:text-blue-700">Generar Constancia</a>
                                                    @else
                                                        @if($request->status_convenio==null || $request->status_convenio==0)

                                                        @else
                                                            <a href="{{ route('admiShowRequest', $request->id) }}" class="text-blue-500 hover:text-blue-700">Ver Solicitud</a>
                                                    @endif
                                                     @endif
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <p class="text-red-500">Nota: Cuando no aparezcan acciones para una solicitud significa que falta validacion de convenio por parte del departamento de Vinculacion, por lo cual le pedimos esperar dicha respuesta.</p>
                            </div>
                            <div class="form-group text-center">
                                        <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
</x-app-layout>
