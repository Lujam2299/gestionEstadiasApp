<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstanciaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

/*Route::get('/admindashboard', [EstanciaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admi.adminDashboard');*/
Route::get('/dashboard', [EstanciaController::class,'index'])->middleware(['auth', 'verified'])->name('adminDashboard');
Route::get('/user_dashboard', [EstanciaController::class,'indexUser'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Usuario admin
    Route::get('/crear_estancia', [EstanciaController::class, 'create'])->name('crearEstancia');
    Route::get('/ver_solicitudes', [EstanciaController::class, 'showSolicitudes'])->name('solicitudes');
    Route::get('/historico_solicitudes', [EstanciaController::class, 'historicoSolicitudes'])->name('historico-solicitudes');

    Route::post('/guardar-estancia', [EstanciaController::class, 'guardar'])->name('guardar-estancia');
    Route::get('/ver-estancia/{id}', [EstanciaController::class, 'showEstancia'])->name('verEstancia');
    Route::delete('/eliminar-estancia/{id}', [EstanciaController::class, 'eliminar'])->name('eliminar-estancia');
    Route::get('/estancia/{estancia}/edit', [EstanciaController::class, 'edit'])->name('estancia.edit');
    Route::put('/estancia/{estancia}', [EstanciaController::class, 'update'])->name('estancia.update');


    //rutas usuario user
    Route::get('/user-solicitudes', [SolicitudEstanciaController::class, 'index'])->name('userSolicitudes');
    Route::get('/ver_estancia/{id}', [EstanciaController::class, 'showUserEstancia'])->name('showUserEstancia');


});

require __DIR__.'/auth.php';

/*
/*
//migracion

*/


/*
*/
