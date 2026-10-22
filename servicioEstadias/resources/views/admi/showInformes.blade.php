<x-app-layout>
    <x-admin-layout></x-admin-layout><br>
    <x-username-layout />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <div class="min-w-screen py-5 flex items-center justify-center">
                    <div class="bg-white text-gray-600 rounded-lg shadow-xl w-full">
                        @if(session('success'))
                            <div class="bg-green-100 border-t border-b border-green-500 text-green-700 px-4 py-3" role="alert">
                                <p class="font-bold">Éxito</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="bg-red-100 border-t border-b border-red-500 text-red-700 px-4 py-3" role="alert">
                                <p class="font-bold">Error</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        @endif
                        <div class="overflow-x-auto">
                            <h1 class="text-2xl text-center py-4">Informes en espera de respuesta</h1>
                            <div class="card p-4">
                                <table class="min-w-full bg-white">
                                    <thead class="text-black-500">
                                        <tr>
                                            <th class="w-1/4 py-2">Nombre</th>
                                            <th class="w-1/4 py-2">Nombre Estancia</th>
                                            <th class="w-1/4 py-2">Constancia de Liberación</th>
                                            <th class="w-1/4 py-2">Informe Final</th>
                                            <th class="w-1/4 py-2">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700">
                                        @foreach($informes as $informe)
                                            @if($informe->solicitud->status != 5)
                                                <tr>
                                                    <td class="border px-4 py-2">{{ $informe->nombre }}</td>
                                                    <td class="border px-4 py-2">{{ $informe->solicitud->estancia->nombre }}</td>
                                                    <td class="border px-4 py-2">
                                                        <a href="{{ url($informe->ruta_constancia) }}" target="_blank" class="text-blue-500 underline">
                                                            Descargar Constancia
                                                        </a>
                                                    </td>
                                                    <td class="border px-4 py-2">
                                                        <a href="{{ url($informe->ruta_oficio) }}" target="_blank" class="text-blue-500 underline">
                                                            Descargar Informe
                                                        </a>
                                                    </td>
                                                    <td class="border px-4 py-2">
                                                        <form method="POST" action="{{ route('aceptar.informe', $informe->solicitud->id) }}">
                                                            @csrf
                                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded">Aceptar</button>
                                                        </form>
                                                        <button onclick="openModal({{ $informe->solicitud->id }})" class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded mt-2">Rechazar</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    {{ $informes->links() }}
                                </table>
                                
                                <div class="form-group text-center py-3">
                                    <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">Regresar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class=" w-full mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Agregar Observación</h3>
                            <div class="mt-5 w-full">
                                <textarea id="observacion" name="observacion" class="p-6 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="submitForm()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Enviar</button>
                    <button onclick="closeModal()" type="button" class=" w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <form id="rechazo-form" method="POST" action="{{ route('rechazar.informe', 0) }}">
        @csrf
        <input type="hidden" name="observacion" id="input-observacion">
    </form>

    <script>
        function openModal(id) {
            document.getElementById('rechazo-form').action = "{{ route('rechazar.informe', '') }}/" + id;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('input-observacion').value = document.getElementById('observacion').value;
            document.getElementById('rechazo-form').submit();
        }
    </script>
</x-app-layout>

