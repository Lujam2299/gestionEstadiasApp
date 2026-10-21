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
        // Validar archivos
        $request->validate([
            'constancia' => 'required|file|mimes:pdf|max:2048',
            'informe' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = Auth::user();
        $solicitud = Solicitudes::where('email', $user->email)
                        ->whereIn('status', [2, 5])
                        ->firstOrFail();
        $estancia = Estancia::findOrFail($solicitud->id_estancia);
        $solicitud->status=4;
        $solicitud->observaciones='Favor de esperar a la respuesta a su envío.';
        $solicitud->save();        

        // Crear registro en la base de datos
        $informe = new Informe();
        $informe->nombre = $user->name;
        $informe->id_solicitud = $solicitud->id;
        //$informe->save();

         // Crear directorio de almacenamiento en public
         $folderPath = public_path('Informes/' . $informe->id . '_' . $user->name);
         if (!File::exists($folderPath)) {
             File::makeDirectory($folderPath, 0755, true);
         }
 
         // Guardar archivo de constancia
         if ($request->hasFile('constancia')) {
             $constanciaPath = $folderPath . '/constancia_' . time() . '.pdf';
             $request->file('constancia')->move($folderPath, 'constancia_' . time() . '.pdf');
             $informe->ruta_constancia = 'Informes/' . $informe->id . '_' . $user->name . '/' . basename($constanciaPath);
         }
 
         // Guardar archivo de oficio
         if ($request->hasFile('informe')) {
             $oficioPath = $folderPath . '/oficio_' . time() . '.pdf';
             $request->file('informe')->move($folderPath, 'oficio_' . time() . '.pdf');
             $informe->ruta_oficio = 'Informes/' . $informe->id . '_' . $user->name . '/' . basename($oficioPath);
         }
 
         // Guardar registro en la base de datos
         $informe->save();

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
            $solicitud->observaciones = $request->input('observacion');
            $solicitud->observaciones = "Detalles en archivos, favor de enviar informes finales de nuevo.";
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
        $request->validate([
            'constancia_pdf' => 'required|mimes:pdf|max:2048',
        ]);

        // Obtener la solicitud y la estancia
        $solicitud = Solicitudes::findOrFail($id);
        $solicitud->status = 7;
        $solicitud->save();
        $estancia = Estancia::findOrFail($solicitud->id_estancia);

        // Obtener el nombre del usuario y el nombre de la estancia
        $usuario = Auth::user()->nombre;
        $nombreEstancia = $estancia->nombre;

        // Crear la ruta de la carpeta
        $folderPath = public_path('ConstanciasAcreditacion/' . $usuario . '/' . $nombreEstancia);

        // Verificar si la carpeta existe, si no, crearla
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        // Guardar el archivo de constancia en la carpeta
        $constanciaPath = $request->file('constancia_pdf')->move(
            $folderPath,
            'constancia_' . time() . '.pdf'
        );

        // Obtener el informe existente o crear uno nuevo
        $informe = Informe::where('id_solicitud', $id)->first();
        if (!$informe) {
            $informe = new Informe();
            $informe->id_solicitud = $id;
        }

        // Actualizar el campo constancia_tec_terminacion
        $informe->constancia_tec_terminacion = 'ConstanciasAcreditacion/' . $usuario . '/' . $nombreEstancia . '/' . basename($constanciaPath);
        $informe->save();

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
