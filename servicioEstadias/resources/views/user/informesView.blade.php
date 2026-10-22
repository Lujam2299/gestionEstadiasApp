<x-app-layout>
    <x-user-layout></x-user-layout><br>
    <x-username-layout />
    
    <div class="container mx-auto py-5">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h1 class="text-2xl font-bold mb-4 text-center">Archivos para estancia Aceptada</h1>
                </div>
                <div class=" card-body flex items-center justify-between mb-4">
                    <table class="table">
                        <thead>
                            <th>No.</th>
                            <th>Archivo</th>
                            <th>Enlace de Descarga</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1.- </td>
                                <td><h2 class="text-lg">Carta de Presentación</h2></td>
                                <td> 
                                    <a href="{{ route('descargarCarta', $solicitud->id) }}" class="text-blue-500 underline">
                                        Descargar
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2.- </td>
                                <td><h2 class="text-lg">Oficio de Comisión</h2></td>
                                <td><a href="{{ route('descargar-oficio', $solicitud->id) }}" class="text-blue-500 underline">Descargar</a></td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            
                <div class="card-footer text-center">
                    <a href="{{ route('userSolicitudes') }}" class="inline-block bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">Regresar</a>
                </div>
            </div>

        </div>
    </div>
    
</x-app-layout>

