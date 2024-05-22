<x-app-layout>
    <x-admin-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                    <div class="min-w-screen py-5 flex items-center justify-center">
                        <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                            <div class="overflow-x-auto">
                                <h1 class="flex items-center justify-center text-3xl">Todas las Solicitudes</h1>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Docente</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Fecha de Solicitud</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allRequests as $request)
                                            <tr>
                                                <td>{{ $request->id }}</td>
                                                <td>{{ $request->docente }}</td>
                                                <td>{{ $request->email }}</td>
                                                <td>
                                                    @if($request->status==0)
                                                        En revisión
                                                    @elseif($request->status==1)
                                                        Observaciones Realizadas
                                                    @elseif($request->status==2)
                                                        Aceptada
                                                    @elseif($request->status==3)
                                                        Rechazada
                                                    @elseif($request->status==4)
                                                        Informes Finales recibidos
                                                    @elseif($request->status==5)
                                                        Observaciones Enviadas
                                                    @elseif($request->status==6)
                                                        Estancia Terminada, constancia pendiente de envío
                                                    @elseif($request->status==7)
                                                        Estancia Terminada y liberada        
                                                    @endif
                                                </td>
                                                <td>{{ $request->fecha_solicitud }}</td>
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group text-center">
                                        <a href="{{ route('adminDashboard') }}" class="btn btn-secondary">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
</x-app-layout>
<x-footer></x-footer>
