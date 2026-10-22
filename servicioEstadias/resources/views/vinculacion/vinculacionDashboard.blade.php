<?php
use Carbon\Carbon;
?>
<x-app-layout>
    <x-vinculacion-layout>

    </x-vinculacion-layout><br>
    <x-username-layout />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
        <div class="flex py-12">
            <div class="w-full justify-center">
            <div class="py-5 flex items-center justify-center">
                        <div class="bg-white text-gray-600 rounded-lg shadow-xl w-full">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif   
                        <div class="bg-white">
                                <h1 class="flex items-center justify-center text-3xl">Solicitudes pendientes</h1>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Docente</th>
                                            <th>Email</th>
                                            <th>Empresa</th>
                                            <th>Estado</th>
                                            <th>Fecha de Solicitud</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estancias as $request)
                                        @if($request->status_convenio==2||$request->status_convenio==1)

                                        @else
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $request->docente }}</td>
                                                <td>{{ $request->email }}</td>
                                                <td>{{ $request->empresa }}</td>
                                                <td>
                                                    @if($request->status==0)
                                                        En revisión
                                                    @elseif($request->status==1)
                                                    Otro 
                                                    @endif
                                                </td>
                                                <td> {{ \Carbon\Carbon::parse($request->fecha_solicitud)->format('d-m-Y') }}</td>
                                                <td>
                                                    <a class="text-blue-500" href="{{route('MostrarSolicitudVinculacion',$request->id)}}">Responder</a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>                                 
                                </table>
                            </div>
                           
                        </div>
                    </div>
            </div>
        </div>
        </div>
    </div>
    
</x-app-layout>