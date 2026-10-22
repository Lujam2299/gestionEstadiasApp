<?php
use Carbon\Carbon;
?>
<x-app-layout>
    <x-admin-layout>

    </x-admin-layout><br>
    <x-username-layout />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <div class="w-full flex flex-wrap justify-center">
                <div class="w-3/4 px-6">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="text-3xl">Detalles de la Estancia</h1>
                        </div>
                        <div class="card-body">
                            <img src="{{ asset('images/tec.jpg') }}" alt="Imagen de la estancia" class="img-fluid" height="250" width="250">
                            <hr><br>
                            <h5 class="card-title">Nombre: {{ $estancia->nombre }}</h5>
                            <hr><br>
                            <p class="card-text">Empresa/Institución: {{ $estancia->empresa }}</p>
                            <hr><br>
                            <p class="card-text">Fecha de apertura de convocatoria: {{ \Carbon\Carbon::parse($estancia->fecha_convocatoria)->format('d-m-Y') }}</p>
                            <hr><br>
                            <p class="card-text">Fecha de cierre de convocatoria: {{ \Carbon\Carbon::parse($estancia->fecha_cierre)->format('d-m-Y') }}</p>
                            <hr><br>
                            <p class="card-text">Archivo de Convocatoria:
                                <a class="text-blue-500 underline" href="{{ asset($estancia->archivo_convocatoria) }}" target="_blank">Ver PDF</a>
                            </p><br>
                        </div>
                        <div class="form-group items-center card-footer text-center">
                        <a href="{{ route('estancia.edit', $estancia->id) }}" class="btn btn-success">Editar</a>
                            <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">Regresar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
