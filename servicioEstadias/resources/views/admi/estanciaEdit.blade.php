<x-app-layout>
    <x-admin-layout><br>
    <x-username-layout />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex flex-wrap justify-center">
                    <div class="w-3/4 px-6">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="text-3xl">Editar Estancia</h1>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('estancia.update', $estancia->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control rounded" id="nombre" name="nombre" value="{{ $estancia->nombre }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_convocatoria">Fecha de apertura convocatoria:</label>
                                        <input type="date" class="form-control rounded" id="fecha_convocatoria" name="fecha_convocatoria" value="{{ $estancia->fecha_convocatoria }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_cierre">Fecha de cierre convocatoria:</label>
                                        <input type="date" class="form-control rounded" id="fecha_cierre" name="fecha_cierre" value="{{ $estancia->fecha_cierre }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="archivo_convocatoria">Archivo de la convocatoria:</label>
                                        
                                        @if($estancia->archivo_convocatoria)
                                            <p>
                                                <a href="{{ asset($estancia->archivo_convocatoria) }}" target="_blank" class="text-blue-500 underline">
                                                    {{ basename($estancia->archivo_convocatoria) }}
                                                </a>
                                            </p>
                                        @else
                                            <p class="text-muted">No hay archivo de convocatoria disponible.</p>
                                        @endif
                                        
                                        <input type="file" class="form-control rounded" id="archivo_convocatoria" name="archivo_convocatoria">
                                    </div>
                                    <div class="form-group">
                                        <label for="requisitos">Requisitos:</label>
                                        <select multiple class="form-control rounded" id="requisitos" name="requisitos[]">
                                        @foreach ($requisitos as $requisito)
                                        <option value="{{ $requisito->id }}" {{ in_array($requisito->id, $estancia->estanciaRequisitos ? json_decode($estancia->estanciaRequisitos->id_requisitos, true) : []) ? 'selected' : '' }}>
                                            {{ $requisito->nombre }}
                                        </option>
                                        @endforeach
                                        </select>
                                    </div><br>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">Cancelar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
</x-app-layout>

