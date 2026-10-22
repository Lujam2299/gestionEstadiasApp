<x-app-layout>
    <x-user-layout></x-user-layout><br>
    <x-username-layout /><br>
    <div>
    @if($solicitud)
    <div class="float-both flex items-center justify-center mt-8">
        <div class="card w-4/5 px-4 py-5">
        <form method="POST" action="{{route('uploadinformes')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h1 class="text-2xl">Formulario para subida de Informes</h1>
                </div>
                <div class="card-body form-control">
                    <div class="form-group">
                        <label class="font-bold text-1xl" for="constancia">Constancia de liberación:</label><br>
                        
                        @if($constancia)
                            <p>
                                Archivo actual: 
                                <a href="{{ asset($constancia) }}" target="_blank" class="text-blue-500 underline">
                                    Ver Constancia
                                </a>
                            </p>
                        @endif
                        <input class="form-group" type="file" name="constancia" {{ $camposHabilitados ? '' : 'disabled' }}><br><br>
                    </div>
                    <label class="font-bold" for="informe">Informe Final:</label><br>
                    
                    @if($informe_final)
                        <p>
                            Archivo actual: 
                            <a href="{{ asset($informe_final) }}" target="_blank" class="text-blue-500 underline">
                                Ver Informe Final
                            </a>
                        </p>
                    @endif
                    <input type="file" name="informe" {{ $camposHabilitados ? '' : 'disabled' }}><br><br>
                </div>
                
                    <input type="hidden" name="id_estancia" value="{{ $solicitud->id_estancia }}">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Guardar</button> &nbsp;&nbsp;
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">Regresar</a>

            </form>
            @if(!$camposHabilitados)
                <p class="text-red-500">Nota Adicional: Si los campos de subida de archivos están deshabilitados es porque usted ya ha hecho un envío de archivos, espere a que sean revisados y le envíen una respuesta antes de poder volver a enviar archivos.</p>
            @endif
        </div>
    </div>
    @else
    <br>
        <div class="card py-5 flex justify-center float-center">
            <div class="card-header">
                <h1 class="text-1xl">
                Esta pestaña no está disponible, esto debido a que de momento no cuenta con estancias en proceso o Aceptadas.
                </h1>
            </div>
            <div class="card-body">
            <div class="card-footer text-center">
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">Regresar</a>
                </div>
            </div>
        </div>
        
    @endif
    </div>
</x-app-layout>

