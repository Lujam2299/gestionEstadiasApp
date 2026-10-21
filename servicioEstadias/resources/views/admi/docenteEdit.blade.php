<x-app-layout>
    <x-admin-layout><br>
    <x-username-layout />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-x-auto">
                @if (session('success'))
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
                    <div class="min-w-screen py-5 flex items-center justify-center">
                        <div class="bg-gray-200 text-gray-600 rounded-lg shadow-xl w-full">
                            <div class="card">
                            <div class="overflow-x-auto">
                                <div class="card-header">
                                    <h1 class="text-center text-3xl">Lista de Docentes</h1>
                                </div>
                                <div class="card-body">
                                <form method="POST" action="{{route('docente.update', $docente->id)}}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="name">Nombre:</label>
                                        <input class="form-control rounded" type="text" id="name" name="name" value="{{ $docente->name }}" disabled>
                                    </div>
                                        <br>
                                    <div class="form-group">
                                        <label for="email">Correo:</label>
                                        <input class="form-control rounded" type="text" id="email" name="email" value="{{ $docente->email }}" disabled>
                                    </div>
                                        <br>
                                    <div class="form-group">
                                        <label for="curp">CURP:</label>
                                        <input class="form-control rounded" type="text" id="curp" name="curp" value="{{ $docente->curp }}">
                                    </div><br>
                                    <div class="form-group">
                                        <label for="rfc">RFC:</label>
                                        <input class="form-control rounded" type="text" id="rfc" name="rfc" value="{{ $docente->rfc }}" >
                                    </div>
                                        <br>
                                    <div class="form-group">
                                        <h4><label for="nombramiento">Nombramiento:</label></h4>
                                        <input class="form-control rounded" type="text" id="nombramiento" name="nombramiento" value="{{ $docente->nombramiento }}" >
                                    </div>
                                        <br>
                                       <!-- <label for="rfc">Academia:</label>
                                        <input class="form-control rounded" type="text" id="academia" name="academia" value="{{ $docente->academia }}" >
-->                                 
                                        <label for="password">Contraseña Actual:</label>
                                        <input class="form-control rounded" type="password" id="password" name="password" value="" disabled>
                                    <br>
                                    <label for="new_password">Nueva Contraseña:</label>
                                    <input class="form-control rounded" type="password" id="new_password" name="new_password">
                                        <br>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        <a href="{{ route('showUsers') }}" class="btn btn-secondary">Regresar</a>

                                    </form>
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
<x-footer></x-footer>