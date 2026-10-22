        @livewireStyles
        @livewireScripts
<x-app-layout>
    <x-vinculacion-layout></x-vinculacion-layout><br>
    <x-username-layout /><br>

    @if (session('success'))
    <br>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Cerrar</title>
                    <path d="M14.348 14.849a.5.5 0 0 1-.708 0L10 10.707l-3.64 3.642a.5.5 0 1 1-.708-.708l3.64-3.641-3.64-3.64a.5.5 0 1 1 .708-.708l3.64 3.64 3.64-3.64a.5.5 0 1 1 .708.708L10.707 10l3.641 3.64a.5.5 0 0 1 0 .708z"/>
                </svg>
            </span>
        </div>
    @endif
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center py-12">
            <div class="w-full bg-white text-gray-600 rounded-lg shadow-xl">
                <h1 class="text-2xl text-center mb-6">Lista de Convenios</h1>
                <div class="mb-4 flex px-6">
                    <a href="{{ route('convenios.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Añadir Nuevo Convenio
                    </a>
                </div>
                <div class="px-6">
                    @livewire('convenio-search')
                </div>
            </div>
        </div>
    </div>
</div>
  
</x-app-layout>
