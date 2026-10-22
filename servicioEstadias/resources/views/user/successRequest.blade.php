<x-app-layout>
    <x-user-layout>

    </x-user-layout><br>
    <x-username-layout />
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">   
                <div class="min-w-screen py-5 flex items-center justify-center">
                    <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                        <div style="display: flex;">
                            <div style="flex: 1;">
                                <h5>Solicitud Generada Exitosamente!</h5>
                            </div>
                            
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="text-center mt-4">
       <a href="{{ route('dashboard') }}" class="inline-block bg-gray-500 px-2 py-2 rounded-md text-white hover:bg-gray-600">Regresar</a>
    </div>

</x-app-layout>
