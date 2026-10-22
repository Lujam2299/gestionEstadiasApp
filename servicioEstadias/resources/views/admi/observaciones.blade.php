<x-app-layout>
    <x-admin-layout>

    </x-admin-layout><br>
    <x-username-layout />

    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                    <div class="min-w-screen py-5 flex items-center justify-center">
                        <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                            <div class="overflow-x-auto">
                                <form method="POST" action="{{ route('enviar-observacion',$solicitud->id )}}">
                                    @csrf
                                    <h3 class="text-3xl" for="observaciones">Observaciones:</label>
                                    <br>
                                    <textarea style="font-size:18px;" name="observaciones" id="observaciones" rows="4" cols="50"></textarea>
                                    <br><br>
                                    <button style="font-size:18px;"class="py-2 px-2 bg-blue-500 text-white rounded text-sm" type="submit">Enviar Observación</button>
                                    <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">Regresar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
