<x-app-layout>
    <x-admin-layout><br>
    <x-username-layout />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                    <div class="min-w-screen py-5 flex items-center justify-center">
                        <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                            <div class="card">
                            <div class="overflow-x-auto">
                                <div class="card-header">
                                    <h1 class="text-center text-3xl">Registro Actualizado exitosamente.</h1>
                                </div>
                               <div class="card-body">
                               <div class="form-group text-center">
                                    <a href="{{ route('showUsers') }}" class="btn btn-secondary">Regresar</a>
                                </div>
                               </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
</x-app-layout>
