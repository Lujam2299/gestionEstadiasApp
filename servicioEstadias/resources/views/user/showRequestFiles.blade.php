<x-app-layout class="py-0">
    <x-user-layout></x-user-layout><br>
    <x-username-layout />
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">   
            <div class="min-w-screen py-5 flex items-center justify-center">
            @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                    <h1 class="flex items-center justify-center text-2xl">Archivos de la Solicitud</h1>
                    <form method="POST" action="{{ route('userUpdateRequest', $solicitud->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <table class="table">
                            <thead>
                                <th>Nombre del Requisito</th>
                                <th>Archivo Adjunto</th>
                                <th>Nuevo Archivo</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>

                           @foreach ($requisitos as $index => $requisito)
    <tr>
        <td class="border px-4 py-2">{{ $requisito->nombre }}</td>
        <td class="border px-4 py-2">
            @if (!empty($rutasArchivos[$index]['archivo']))
                <a class="text-blue-500 underline" href="{{ asset($rutasArchivos[$index]['archivo']) }}">
                    {{ basename($rutasArchivos[$index]['archivo']) }}
                </a>
            @else
                <span class="text-red-500">Archivo no disponible</span>
            @endif
        </td>
        <td class="border px-4 py-2" id="file_display_{{ $index }}"></td>
        <td>
            <label for="file_{{ $index }}" class="text-blue-500 underline cursor-pointer">Modificar</label>
            <input type="file" id="file_{{ $index }}" class="hidden" onchange="updateFileName(this, 'file_display_{{ $index }}')" name="nuevo_archivo_{{ $index }}">
        </td>
    </tr>
@endforeach

                            </tbody>
                        </table>

                        <div class="mt-4 bg-white">
                            <h4><strong>Información adicional</strong></h4>
                            <div class="mb-3">
                                <label for="empresa" class="form-label text-gray-800">Empresa donde se realizará la Estancia:</label>
                                <input type="text" class="form-control rounded" id="empresa" name="empresa" value="{{ $solicitud->empresa }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="proyecto" class="form-label text-gray-800">Proyecto a realizar:</label>
                                <input type="text" class="form-control rounded" id="proyecto" name="proyecto" value="{{ $solicitud->proyecto }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="plan_estudios" class="form-label text-gray-800">Plan de estudios:</label>
                                <input type="text" class="form-control rounded" id="plan_estudios" name="plan_estudios" value="{{ $solicitud->plan_estudios }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="giro_empresa" class="form-label text-gray-800">Giro de la empresa:</label>
                                <input type="text" class="form-control rounded" id="giro_empresa" name="giro_empresa" value="{{ $solicitud->giro_empresa }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="area_complementacion" class="form-label text-gray-800">Área en la que desea complementar sus conocimientos:</label>
                                <input type="text" class="form-control rounded" id="area_complementacion" name="area_complementacion" value="{{ $solicitud->area_complementacion }}" required>
                            </div>
                        </div>
                        <button type="submit" class="inline-block bg-green-500 px-4 py-2 rounded-md text-white hover:bg-green-600 ml-4">Guardar cambios</button>
                        <a href="{{ route('userSolicitudes') }}" class="inline-block bg-gray-500 px-4 py-2 rounded-md text-white hover:bg-red-600">Regresar</a>
                    </form>
                    <p class="text-red-500">Nota: En caso de requerir modificar alguno de los archivos, tenga en cuenta que los nuevos archivos deben ser también en formato PDF, de lo contrario no se podrán guardar los cambios.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4"></div>
</x-app-layout>
<x-footer></x-footer>

<script>
    function updateFileName(input, displayId) {
        const display = document.getElementById(displayId);
        if (input.files.length > 0) {
            display.innerText = input.files[0].name;
        }
    }
</script>