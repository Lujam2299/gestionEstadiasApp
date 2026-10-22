<x-app-layout>
    <x-user-layout>

    </x-user-layout><br>
    <x-username-layout />
    <div class="py-12 w-full flex flex-wrap justify-center">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" w-full flex flex-wrap justify-center">
            <div class="w-3/4 px-6">
                <p>Para que su solicitud sea tomada en cuenta es necesario cumplir con los requisitos establecidos en la convocatoria y, a su vez, descargar, llenar con sus datos y subir cada uno de los archivos que se muestran a continuación.</p>
                <h3>Nombre de la estadía: {{ $estancia->nombre }}</h3>

                <form method="POST" action="{{ route('generar-solicitud', $estancia->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    <input type="hidden" name="docente" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="fecha_solicitud" value="{{ now()->toDateString() }}">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Num.</th>
                                <th>Nombre del Archivo</th>
                                <th>Descargar</th>
                                <th>Adjuntar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($id_requisitos as $requisito)
                            <tr>
                                <td>{{ $loop->iteration }}.-</td>
                                <td>{{ $requisito->nombre }}</td>
                                <td>
                                @if ($requisito->descargable == 1)
                                <a href="{{ url('archivos/' . basename($requisito->archivo_requisito)) }}" download>
                                    <!--<a href="{{ asset($requisito->archivo_requisito) }}" download>-->
                                        <img src="/images/download.png" alt="Descargar" width="29" height="29">
                                    </a>
                                @else
                                    No disponible para descarga
                                @endif
                            </td>

                            <td>
                                <label for="file-input-{{ $requisito->id }}">
                                    <img id="upload-icon-{{ $requisito->id }}" src="/images/upload.jpg" alt="" width="50" height="50">
                                </label>
                                <input id="file-input-{{ $requisito->id }}" type="file" name="archivo_adjunto_{{ $requisito->id }}" style="display: none;" onchange="mostrarNombreArchivo('{{ $requisito->id }}')">
                                <span id="nombre-archivo-{{ $requisito->id }}" style="display: none;"></span>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4 bg-white text-bold px-6">
                        <h2><strong>Información adicional</strong></h2><br>
                        <div>
                            <label for="empresa" class="form-label">Empresa donde se realizará la estancia:</label>
                            <input type="text" class="form-control rounded" id="empresa" name="empresa" list="lista-empresas" required autocomplete="off">
                            <datalist id="lista-empresas">
                                
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label for="titular_empresa">Titular de la Empresa:</label>
                            <input type="text" id="titular_empresa" name="titular_empresa" class="form-control rounded">
                        </div>

                        <div class="mb-3">
                            <label for="puesto_titular">Puesto del Titular:</label>
                            <input type="text" id="puesto_titular" name="puesto_titular" class="form-control rounded">
                        </div>
                        <div class="mb-3">
                            <label for="proyecto" class="form-label">Proyecto a realizar:</label>
                            <input type="text" class="form-control rounded" id="proyecto" name="proyecto" required>
                        </div>
                        <div class="mb-3">
                            <label for="objetivo">Objetivo:</label>
                            <textarea id="objetivo" name="objetivo" class="form-control rounded"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="plan_estudios" class="form-label">Plan de estudios:</label>
                            <select class="form-control rounded" id="plan_estudios" name="plan_estudios" required>
                                <option value="" disabled selected>Seleccione una carrera</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->plan_de_estudios }}">
                                        {{ $carrera->nombre }} ({{ $carrera->plan_de_estudios }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="giro_empresa" class="form-label">Giro de la empresa:</label>
                            <input type="text" class="form-control rounded" id="giro_empresa" name="giro_empresa" required>
                        </div>
                        <div class="mb-3">
                            <label for="area_complementacion" class="form-label">Área en la que desea complementar sus conocimientos:</label>
                            <input type="text" class="form-control rounded" id="area_complementacion" name="area_complementacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="periodo_duracion" class="form-label">Periodo de duración de la estancia:</label>
                            <input type="text" class="form-control rounded" id="periodo_duracion" name="periodo_duracion" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="inicio_estancia">Inicio de la Estancia:</label>
                            <input type="date" id="inicio_estancia" name="inicio_estancia" class="form-control rounded">
                        </div>

                        <div class="mb-3">
                            <label for="fin_estancia">Fin de la Estancia:</label>
                            <input type="date" id="fin_estancia" name="fin_estancia" class="form-control rounded">
                        </div>
                    </div>

                    <div class="mt-4">
                        <b><p>Nota: Antes de enviar la solicitud verifique que haya añadido los documentos de TODOS los requisitos necesarios, de no ser así, su solicitud será rechazada automáticamente.</p></b>
                        <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                        <a href="{{ route('showUserEstancia', $estancia->id) }}" class="btn btn-secondary">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function mostrarNombreArchivo(numero) {
        var fileInput = document.getElementById('file-input-' + numero);
        var uploadIcon = document.getElementById('upload-icon-' + numero);
        var nombreArchivo = document.getElementById('nombre-archivo-' + numero);

        if (fileInput.files.length > 0) {
            var nombre = fileInput.files[0].name;
            nombreArchivo.textContent = nombre;
            uploadIcon.style.display = 'none';
            nombreArchivo.style.display = 'inline-block';
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const empresaInput = document.getElementById('empresa');
        const datalist = document.getElementById('lista-empresas');

        empresaInput.addEventListener('input', async () => {
            const query = empresaInput.value;

            if (query.length >= 1) { 
                try {
                    const response = await fetch(`/empresas?search=${query}`);
                    if (!response.ok) throw new Error('Error al obtener las empresas');

                    const empresas = await response.json();

                    // Limpiar datalist
                    datalist.innerHTML = '';

                    // Añadir opciones al datalist
                    empresas.forEach(empresa => {
                        const option = document.createElement('option');
                        option.value = empresa.nombre;
                        datalist.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        });
    });
</script>

</x-app-layout>
