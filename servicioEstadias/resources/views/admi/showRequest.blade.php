<x-app-layout class="py-0">
    <x-admin-layout></x-admin-layout><br>
    <x-username-layout ></x-username-layout>
        <div class="py-12 w-full flex flex-wrap justify-center">   
            <div class="w-3/4 py-5 flex items-center justify-center bg-white rounded">
                <div class="bg-white text-gray-900 rounded-lg shadow-xl w-full  px-6">
                    <h1 class="flex items-center justify-center text-3xl">Detalles de la Solicitud</h1>
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th class="text-bold">Requisitos de la Solicitud</th>
                                <th class="text-bold">Archivos Adjuntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($rutasArchivos))
                                @foreach ($requisitos as $index => $requisito)
                                    <tr>
                                        <td>{{ $requisito->nombre }}</td>
                                        <td>
                                            @if (!empty($rutasArchivos[$index]))
                                                <a class="text-blue-500 underline" href="{{ $rutasArchivos[$index] }}" target="_blank">
                                                    {{ basename($rutasArchivos[$index]) }}
                                                </a><br>
                                            @else
                                                <span class="text-red-500">Archivo no disponible</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No hay archivos adjuntos disponibles</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="mt-4 bg-white px-6 mb-4">
                                    <h4><strong>Información adicional</strong></h4>
                                    <div class="mb-3">
                                        <label for="empresa" class="form-label text-gray-800">Empresa donde se realizará la Estancia:</label>
                                        <input type="text" class="form-control rounded" id="empresa" name="empresa" value="{{ $solicitud->empresa }}" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="titular_empresa" class="form-label text-gray-800">Titular de la empresa:</label>
                                        <input type="text" class="form-control rounded" id="titular_empresa" name="titular_empresa" value="{{ $solicitud->titular_empresa }}" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="puesto_titular" class="form-label text-gray-800">Puesto del titular de la empresa:</label>
                                        <input type="text" class="form-control rounded" id="puesto_titular" name="puesto_titular" value="{{ $solicitud->puesto_titular }}" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="proyecto" class="form-label text-gray-800">Proyecto a realizar:</label>
                                        <input type="text" class="form-control rounded" id="proyecto" name="proyecto" value="{{ $solicitud->proyecto }}" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="objetivo" class="form-label text-gray-800">Objetivo(s) y Justificación:</label>
                                        <textarea id="objetivo" name="objetivo" class="form-control rounded" required readonly>{{ $solicitud->objetivo }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="plan_estudios" class="form-label text-gray-800">Plan de estudios:</label>
                                        <input type="text" class="form-control rounded" id="plan_estudios" name="plan_estudios" value="{{ $solicitud->plan_estudios }}" required readonly>    
                                        </input>
                                    </div>
                                    <div class="mb-3">
                                        <label for="giro_empresa" class="form-label text-gray-800">Giro de la empresa:</label>
                                        <input type="text" class="form-control rounded" id="giro_empresa" name="giro_empresa" value="{{ $solicitud->giro_empresa }}" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="area_complementacion" class="form-label text-gray-800">Área en la que desea complementar sus conocimientos:</label>
                                        <input type="text" class="form-control rounded" id="area_complementacion" name="area_complementacion" value="{{ $solicitud->area_complementacion }}" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inicio_estancia" class="form-label text-gray-800">Inicio de la Estancia:</label>
                                        <input type="text" class="form-control rounded bg-gray-100 " 
                                            id="inicio_estancia" 
                                            name="inicio_estancia" 
                                            value="{{ \Carbon\Carbon::parse($solicitud->inicio_estancia)->translatedFormat('d M Y') }}" 
                                            readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fin_estancia" class="form-label text-gray-800">Fin de la Estancia:</label>
                                        <input type="text" class="form-control rounded bg-gray-100" 
                                            id="fin_estancia" 
                                            name="fin_estancia" 
                                            value="{{ \Carbon\Carbon::parse($solicitud->fin_estancia)->translatedFormat('d M Y') }}" 
                                            readonly>
                                    </div>
                                    <div class="text-center mt-4 flex justify-center">
                                        <form method="POST" action="{{ route('aceptar-solicitud', $solicitud->id) }}" class="mr-2">
                                            @csrf
                                            <button type="submit" class="bg-green-500 px-2 py-2 rounded-md text-white hover:bg-green-600">Aceptar</button>
                                        </form>
                                        <a href="{{ route('observaciones', $solicitud->id) }}" class="mr-2">
                                            <button class="bg-yellow-600 px-2 py-2 rounded-md text-white hover:bg-yellow-600">Observaciones</button>
                                        </a>
                                        <form method="POST" action="{{ route('rechazar-solicitud', $solicitud->id) }}" class="mr-2">
                                            @csrf
                                            <button type="submit" class="bg-red-500 px-2 py-2 rounded-md text-white hover:bg-red-600">Rechazar</button>
                                        </form>
                                        <a href="{{ route('solicitudes') }}">
                                            <button class="bg-gray-500 px-2 py-2 rounded-md text-white hover:bg-gray-600">Regresar</button>
                                        </a>
                                    </div>
                                </div
                    </div>
            </div>
        </div>
</x-app-layout>

