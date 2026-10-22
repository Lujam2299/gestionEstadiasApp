<x-app-layout>
<x-vinculacion-layout></x-vinculacion-layout>
<br>
<x-username-layout></x-username-layout>

<div class="py-12 w-full flex flex-wrap justify-center">
    <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" w-full flex flex-wrap justify-center">
            <div class="w-3/4 px-6 bg-white">
                <h1 class="text-2xl mb-6">Añadir Nuevo Convenio</h1>
                <form action="{{ route('convenios.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Empresa:</label>
                        <input type="text" name="nombre" id="nombre" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="fecha_vigencia" class="block text-sm font-medium text-gray-700">Fecha de Vigencia:</label>
                        <input type="date" name="fecha_vigencia" id="fecha_vigencia" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="archivo_convenio" class="block text-sm font-medium text-gray-700">Archivo del Convenio:</label>
                        <input type="file" name="archivo_convenio" id="archivo_convenio" required class="mt-1 block w-full">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Guardar Convenio
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
