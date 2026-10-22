<x-app-layout>
    <x-user-layout></x-user-layout><br>
    <x-username-layout /><br>
    <div class="card py-5 mt-7">
        <div class="card-header">
            <h1>Creado con exito.</h1>
        </div>
        <div class="card-body flex items-center justify-center">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary ">Regresar</a>
        </div>
    </div>
    
</x-app-layout>
