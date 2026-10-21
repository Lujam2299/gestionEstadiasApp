<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use App\Models\Informe;
use App\Models\Solicitudes;
use App\Models\Estancia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class InformesController extends Controller
{
    public function guardarInformes(Request $request)
    {
        $request->validate([
            'constancia' => 'required|file|mimes:pdf|max:2048',
            'informe' => 'required|file|mimes:pdf|max:2048',
        ]);
        $newPaths = [];
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$newPaths) {
                $solicitud = Solicitudes::where('email', Auth::user()->email)
                    ->whereIn('status', [2, 5])->latest()->lockForUpdate()->firstOrFail();
                $informe = Informe::where('id_solicitud', $solicitud->id)->first() ?? new Informe();
                $informe->nombre = Auth::user()->name;
                $informe->id_solicitud = $solicitud->id;
                foreach (['constancia' => 'ruta_constancia', 'informe' => 'ruta_oficio'] as $key => $column) {
                    $file = $request->file($key);
                    $name = $key.'_'.$file->hashName();
                    $relative = 'Informes/'.$solicitud->id.'/'.$name;
                    $file->move(public_path('Informes/'.$solicitud->id), $name);
                    $newPaths[] = $relative;
                    $informe->$column = $relative;
                }
                $informe->save();
                $solicitud->status = 4;
                $solicitud->observaciones = 'Favor de esperar a la respuesta a su envío.';
                $solicitud->save();
            });
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) { File::delete(public_path($path)); }
            throw $exception;
        }
        return view('user.uploadInformesSuccess');
    }

    public function showInformes(){
        //$informes= Informe::all();
        $informes = Informe::with('solicitud.estancia')
        ->whereHas('solicitud', function ($query) {
            $query->where('status', 4)
                  ->orWhere('status', 5);
        })
        ->paginate(10);
        return view('admi.showInformes', compact('informes'));
    }

    public function aceptar($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = Solicitudes::findOrFail($id);
        abort_unless(in_array((int) $solicitud->status, [4, 5], true), 422);

        // Actualiza el status y las observaciones
        $solicitud->status = 6;
        $solicitud->observaciones = 'Liberado Correctamente. Estancia Finalizada';
        $solicitud->save();

        // Redirige de vuelta a la vista de informes con un mensaje de éxito
        return redirect()->route('showInformes')->with('success', 'Informe aceptado y solicitud liberada.');
    }

    public function rechazarInforme(Request $request, $id)
    {
        // Buscar la solicitud por ID
        $solicitud = Solicitudes::find($id);
        
        // Verificar si la solicitud existe
        if ($solicitud) {
            // Actualizar el estado y las observaciones
            $solicitud->status = 5;
            $request->validate(['observacion' => 'nullable|string|max:255']);
            $solicitud->observaciones = $request->input('observacion') ?: 'Detalles en archivos, favor de enviar informes finales de nuevo.';
            $solicitud->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('showInformes')->with('success', 'El informe ha sido rechazado.');
        } else {
            // Redirigir con un mensaje de error si no se encuentra la solicitud
            return redirect()->route('showInformes')->with('error', 'No se encontró la solicitud.');
        }
    }

    public function showUploadForm($id)
    {
        $solicitud = Solicitudes::findOrFail($id);
        return view('admi.uploadConstanciaFinal', compact('solicitud'));
    }


    public function uploadConstancia(Request $request, $id)
    {
        $request->validate(['constancia_pdf' => 'required|file|mimes:pdf|max:2048']);
        $newPath = null;
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id, &$newPath) {
                $solicitud = Solicitudes::whereKey($id)->lockForUpdate()->firstOrFail();
                abort_unless((int) $solicitud->status === 6, 422, 'Primero debe liberar el informe.');
                $informe = Informe::where('id_solicitud', $id)->firstOrFail();
                $file = $request->file('constancia_pdf');
                $name = $file->hashName();
                $file->move(public_path('ConstanciasAcreditacion/'.$id), $name);
                $newPath = 'ConstanciasAcreditacion/'.$id.'/'.$name;
                $informe->constancia_tec_terminacion = $newPath;
                $informe->save();
                $solicitud->status = 7;
                $solicitud->save();
            });
        } catch (\Throwable $exception) {
            if ($newPath) { File::delete(public_path($newPath)); }
            throw $exception;
        }
        return redirect()->route('adminDashboard')->with('success', 'Constancia subida exitosamente.');
    }

    public function descargarConstancia($id)
{
    $solicitud = Solicitudes::findOrFail($id);
    abort_unless($solicitud->email === Auth::user()->email || Auth::user()->email === 'desarrolloacademico@tecvalles.mx', 403);
    $idSolicitud = $solicitud->id;
    
    // Buscar el informe correspondiente a la solicitud
    $informe = Informe::where('id_solicitud', $idSolicitud)->first();
    
    // Verificar si se encontró un informe para la solicitud
    if ($informe) {
        // Obtener la ruta relativa al archivo PDF desde el informe
        $rutaArchivo = $informe->constancia_tec_terminacion;
        
        // Intentar abrir el archivo desde la carpeta public
        $rutaCompletaPublic = public_path($rutaArchivo);
        if ($rutaArchivo && is_file($rutaCompletaPublic)) {
            // Abrir el archivo en el navegador
            return response()->file($rutaCompletaPublic);
        }
        
        // Si no se encuentra en la carpeta public, intentar desde la carpeta storage
        $rutaCompletaStorage = storage_path('app/public/' . $rutaArchivo);
        if ($rutaArchivo && is_file($rutaCompletaStorage)) {
            // Abrir el archivo en el navegador
            return response()->file($rutaCompletaStorage);
        }
        
        // Si no se encuentra en ninguna de las carpetas, redirigir con un mensaje de error
        return redirect()->back()->with('error', 'El archivo no existe');
    } else {
        // No se encontró un informe para la solicitud, redirigir con un mensaje de error
        return redirect()->back()->with('error', 'No se encontró el informe para la solicitud');
    }
}

    



}
