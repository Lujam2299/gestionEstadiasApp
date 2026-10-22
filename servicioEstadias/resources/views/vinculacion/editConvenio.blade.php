<x-app-layout>
<x-vinculacion-layout></x-vinculacion-layout>
<br><x-username-layout></x-username-layout>

<div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold text-center mb-6">Editar Convenio</h1>
        <form action="{{ route('convenios.update', $convenio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-input w-full" value="{{ $convenio->nombre }}" required>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block text-gray-700 font-bold mb-2">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-input w-full" value="{{ $convenio->fecha_inicio }}" required>
            </div>

            <div class="mb-4">
                <label for="fecha_vigencia" class="block text-gray-700 font-bold mb-2">Fecha de Vigencia:</label>
                <input type="date" id="fecha_vigencia" name="fecha_vigencia" class="form-input w-full" value="{{ $convenio->fecha_vigencia }}" required>
            </div>

            <div class="mb-4">
                <label for="archivo_convenio" class="block text-gray-700 font-bold mb-2">Archivo Convenio:</label>
                <input type="file" id="archivo_convenio" name="archivo_convenio" class="form-input w-full">
                <p class="text-sm text-gray-500">Dejar este campo vacío si no desea cambiar el archivo actual.</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Actualizar Convenio
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
