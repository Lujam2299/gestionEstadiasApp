<x-app-layout>
    <div class="workspace">
        <a href="{{ route('adminDashboard') }}" class="back-link">← Convocatorias</a>
        <header class="editor-heading"><span class="eyebrow">NUEVA OPORTUNIDAD</span>
            <h1>El siguiente paso<br>empieza aquí.</h1>
            <p>Crea una convocatoria para conectar conocimiento y experiencia.</p>
        </header>
        <div class="editor-grid">
            <form class="editor-card" method="POST" action="{{ route('guardar-estancia') }}" enctype="multipart/form-data">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert"><strong>Revisa los datos antes de continuar.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-section-title"><span>01</span>
                    <h2>Información de la convocatoria</h2>
                </div>
                <div class="field"><label for="nombre">Nombre de la estancia</label><input id="nombre"
                        name="nombre" value="{{ old('nombre') }}" placeholder="Ej. Innovación y desarrollo tecnológico"
                        required></div>
                <div class="field"><label for="empresa">Empresa o institución</label><input id="empresa"
                        name="empresa" value="{{ old('empresa') }}" placeholder="Nombre de la organización receptora"
                        required></div>
                <div class="field-grid">
                    <div class="field"><label for="fecha_convocatoria">Fecha de apertura</label><input type="date"
                            id="fecha_convocatoria" name="fecha_convocatoria" value="{{ old('fecha_convocatoria') }}"
                            required></div>
                    <div class="field"><label for="fecha_cierre">Fecha de cierre</label><input type="date"
                            id="fecha_cierre" name="fecha_cierre" value="{{ old('fecha_cierre') }}" required></div>
                </div>
                <div class="form-section-title"><span>02</span>
                    <h2>Documento y requisitos</h2>
                </div>
                <div class="file-zone field"><label for="archivo_convocatoria">Documento de la convocatoria</label>
                    <p>Selecciona un archivo PDF de hasta 2 MB.</p><input type="file" id="archivo_convocatoria"
                        name="archivo_convocatoria" accept="application/pdf" required>
                </div>
                <fieldset class="requirements-fieldset">
                    <legend>Documentos que deberá presentar el docente</legend>
                    <p class="field-help">Selecciona al menos un requisito.</p>
                    <div class="requirement-list">
                        @foreach ($requisitos as $requisito)
                            @if ($requisito->nombre !== 'Convocatoria')
                                <label><input type="checkbox" name="requisitos[]" value="{{ $requisito->id }}"
                                        @checked(in_array($requisito->id, old('requisitos', [])))><span>{{ $requisito->nombre }}</span></label>
                            @endif
                        @endforeach
                    </div>
                </fieldset>
                <div class="editor-actions"><a class="button-secondary"
                        href="{{ route('adminDashboard') }}">Cancelar</a><button type="submit"
                        class="button-primary">Crear convocatoria <span aria-hidden="true">↗</span></button></div>
            </form>
            <aside class="editor-aside"><span class="eyebrow">ANTES DE PUBLICAR</span>
                <h2>La claridad abre oportunidades.</h2>
                <p>Un nombre descriptivo, fechas precisas y requisitos claros ayudan a que cada docente prepare su
                    participación.</p>
                <div class="aside-tip"><strong>Ten a mano el PDF</strong>
                    <p>Incluye los detalles de participación, objetivos y contacto de la organización receptora.</p>
                </div>
                <div class="aside-tip"><strong>Revisa el calendario</strong>
                    <p>La fecha de cierre debe ser igual o posterior a la apertura.</p>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
