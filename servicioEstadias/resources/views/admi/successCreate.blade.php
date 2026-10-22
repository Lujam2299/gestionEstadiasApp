<x-app-layout>
<x-admin-layout>
    <x-slot name="header">
    </x-slot>
    <br>
    <x-username-layout />
    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                    <div class="min-w-screen py-5 flex items-center justify-center">
                        <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                            <div class="overflow-x-auto">
                                <div class="card">
                                    <div class="card-header">
                                    <h1>Estancia generada exitosamente!</h1>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
       <a href="{{ route('adminDashboard') }}" class="inline-block bg-gray-500 px-2 py-2 rounded-md text-white hover:bg-gray-600">Regresar</a>
    </div>
</x-admin-layout>
</x-app-layout>
