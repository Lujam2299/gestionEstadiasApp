<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Estancia;
use App\Models\Estanciarequisitos;
use App\Models\Requisitos;
use App\Models\Solicitudes;
use App\Models\Convenio;
use App\Models\Carrera;
use Carbon\Carbon;

use Illuminate\Http\Request;

class SolicitudesController extends Controller
{
    //
    public function userCreateSolicitud($id)
    {   
        // Obtener los detalles de la estancia
        $estancia = Estancia::find($id);
        $carreras = Carrera::all();
        if (!$estancia) {
            abort(404);
        }

        // Obtener los requisitos de la estancia
        $requisitosEstancia = EstanciaRequisitos::where('id_estancia', $id)->first();
        
        if (!$requisitosEstancia) {
            abort(404, 'No se encontraron requisitos para la estancia.');
        }
    
        // Convertir el JSON de requisitos en un array de IDs
        $requisitosIds = json_decode($requisitosEstancia->id_requisitos, true) ?? [];
    
        // Obtener los registros de requisitos correspondientes a los IDs
        $id_requisitos = Requisitos::whereIn('id', $requisitosIds)->get();
    
        // Verificar si se encontraron requisitos
        if ($id_requisitos->isEmpty()) {
            abort(404, 'No se encontraron requisitos para la estancia.');
        }
    
    
        return view('user.createRequest', compact('estancia','id_requisitos', 'carreras'));
    }


    public function generarSolicitud(Request $request, $id)
    {
        $estancia = Estancia::findOrFail($id);
        abort_if($estancia->vigente != 0 || $estancia->fecha_cierre < today()->toDateString()
            || $estancia->fecha_convocatoria > today()->toDateString(), 422, 'La convocatoria no está abierta.');
        $estanciaRequisitos = EstanciaRequisitos::where('id_estancia', $id)->firstOrFail();
        $ids = json_decode($estanciaRequisitos->id_requisitos, true) ?? [];
        $rules = $this->solicitudRules();
        $rules['empresa'] = 'required|string|max:255';
        $rules['periodo_duracion'] = 'required|string|max:255';
        foreach ($ids as $requisitoId) {
            $rules['archivo_adjunto_'.$requisitoId] = 'required|file|mimes:pdf,doc,docx|max:2048';
        }
        $data = $request->validate($rules);
        $folder = null;
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id, $ids, $data, &$folder) {
                // Serialize submissions by the same docente to avoid duplicate active requests.
                \App\Models\User::whereKey(Auth::id())->lockForUpdate()->firstOrFail();
                abort_if(Solicitudes::where('email', Auth::user()->email)->whereIn('status', [0, 1, 2, 4, 5, 6])->exists(),
                    422, 'Ya tiene una solicitud en proceso.');
                $solicitud = new Solicitudes();
                $solicitud->forceFill(collect($data)->except(array_map(fn ($id) => 'archivo_adjunto_'.$id, $ids))->all());
                $solicitud->id_estancia = $id;
                $solicitud->email = Auth::user()->email;
                $solicitud->docente = Auth::user()->name;
                $solicitud->fecha_solicitud = today()->toDateString();
                $solicitud->status = 0;
                $solicitud->requisitos = '[]';
                $solicitud->save();
                $folder = public_path('solicitudes/'.$solicitud->id);
                $paths = [];
                foreach ($ids as $requisitoId) {
                    $file = $request->file('archivo_adjunto_'.$requisitoId);
                    $name = $requisitoId.'_'.$file->hashName();
                    $file->move($folder, $name);
                    $paths[] = 'solicitudes/'.$solicitud->id.'/'.$name;
                }
                $solicitud->requisitos = json_encode($paths);
                $solicitud->save();
            });
        } catch (\Throwable $exception) {
            if ($folder) {
                \Illuminate\Support\Facades\File::deleteDirectory($folder);
            }
            throw $exception;
        }
        return redirect()->route('userSolicitudes')->with('success', 'Solicitud enviada correctamente.');
    }

    private function solicitudRules(): array
    {
        return [
            'proyecto' => 'required|string|max:255',
            'plan_estudios' => 'required|string|max:255',
            'giro_empresa' => 'required|string|max:255',
            'area_complementacion' => 'required|string|max:255',
            'titular_empresa' => 'required|string|max:255',
            'puesto_titular' => 'required|string|max:255',
            'objetivo' => 'required|string|max:1000',
            'inicio_estancia' => 'required|date',
            'fin_estancia' => 'required|date|after_or_equal:inicio_estancia',
        ];
    }

    public function index()
    {
        $userId = Auth::user()->email;
        $solicitudes = Solicitudes::where('email', $userId)
        ->orderBy('status', 'asc')
        ->get();

        return view('user.userSolicitudes', compact('solicitudes'));
    }


    public function showRequestFiles($id)
    {
        $solicitud = Solicitudes::findOrFail($id);
        abort_unless($solicitud->email === Auth::user()->email, 403);

        $estanciaRequisitos = EstanciaRequisitos::where('id_estancia', $solicitud->id_estancia)->first();
        $idsRequisitos = json_decode($estanciaRequisitos->id_requisitos);
        $carreras= Carrera::all();
        // Obtener los nombres de los requisitos
        $requisitos = Requisitos::whereIn('id', $idsRequisitos)->get();
        
        // Decodificar el campo requisitos y verificar que contenga arrays con clave 'archivo'
        $rutasArchivos = json_decode($solicitud->requisitos, true) ?? [];
        
        // Normalizar el array para asegurarnos de que todos tengan la clave 'archivo'
        $rutasArchivos = array_map(function($item) {
            return is_array($item) ? $item : ['archivo' => $item];
        }, $rutasArchivos);
    
        $rutasArchivos = array_pad($rutasArchivos, count($requisitos), ['archivo' => null]);
    
        return view('user.showRequestFiles', compact('requisitos', 'rutasArchivos', 'solicitud', 'carreras'));
    }
    

public function mostrarArchivo($id, $nombreArchivo)
{
    $solicitud = Solicitudes::findOrFail($id);
    abort_unless($solicitud->email === Auth::user()->email || in_array(Auth::user()->email, [
        'desarrolloacademico@tecvalles.mx', 'vinculacion@tecvalles.mx',
    ], true), 403);
    abort_if(basename($nombreArchivo) !== $nombreArchivo, 404);
    $rutaArchivo = public_path("solicitudes/{$id}/{$nombreArchivo}");
    
    // Verifica si el archivo existe antes de mostrarlo
    if (file_exists($rutaArchivo)) {
        return response()->file($rutaArchivo);
    } else {
        abort(404, 'Archivo no encontrado');
    }
}



    public function currentRequests()
    {
        // Obtener todas las solicitudes con status diferente de 3
        $currentRequests = Solicitudes::where('status', '!=', 3)->get();

        // Retornar la vista con las solicitudes actuales
        return view('admi.currentRequests', compact('currentRequests'));
    }

    public function allRequests()
    {
        // Obtener todas las solicitudes
        $allRequests = Solicitudes::paginate(10);

        // Retornar la vista con todas las solicitudes
        return view('admi.allRequests', compact('allRequests'));
    }

    public function showRequest($id)
{
    // Obtener la solicitud
    $solicitud = Solicitudes::findOrFail($id);
    $carreras = Carrera::all();

    // Obtener el JSON de requisitos de la tabla estanciaRequisitos
    $estanciaRequisitos = EstanciaRequisitos::where('id_estancia', $solicitud->id_estancia)->first();

    // Decodificar el JSON para obtener los ids de los requisitos
    $idsRequisitos = json_decode($estanciaRequisitos->id_requisitos);

    // Obtener los nombres de los requisitos de la tabla requisitos
    $requisitos = Requisitos::whereIn('id', $idsRequisitos)->get();

    // Decodificar las rutas de los archivos de la solicitud
    $rutasArchivos = json_decode($solicitud->requisitos, true);

    // Asegurarse de que $rutasArchivos es un array y ajustar las rutas
    $rutasArchivos = array_pad($rutasArchivos, count($requisitos), null);

    // Construir rutas completas usando asset()
    $rutasArchivos = array_map(function ($archivo) {
        return $archivo ? asset($archivo) : null;
    }, $rutasArchivos);

    return view('admi.showRequest', compact('solicitud', 'requisitos', 'rutasArchivos', 'carreras'));
}


    public function aceptarSolicitud($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = Solicitudes::findOrFail($id);

        // Actualiza el estado de la solicitud a 2 (Aceptado)
        $solicitud->status = 2;
        $solicitud->observaciones="Solicitud Aceptada.";
        $solicitud->save();

        // Redirige de vuelta a la misma página con un mensaje de éxito
        return view('admi.requestAccepted');
    }

    public function rechazarSolicitud($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = Solicitudes::findOrFail($id);

        // Actualiza el estado de la solicitud a 3 (Rechazado)
        $solicitud->status = 3;
        $solicitud->observaciones="Solicitud Rechazada.";
        $solicitud->save();

        // Redirige de vuelta a la misma página con un mensaje de éxito
        return view('admi.rejectRequest');
    }
    public function observaciones($id)
    {
        // Obtener la solicitud por su ID
        $solicitud = Solicitudes::findOrFail($id);

        // Devolver la vista de observaciones con los datos necesarios
        return view('admi.observaciones', compact('solicitud'));
    }
    
    public function enviarObservacion(Request $request, $id)
    {
        // Validar la solicitud y obtener los datos del formulario
        $request->validate([
            'observaciones' => 'required|string',
        ]);

        // Obtener la solicitud
        $solicitud = Solicitudes::findOrFail($id);

        // Actualizar la solicitud con las observaciones
        $solicitud->status=1;
        $solicitud->observaciones = $request->input('observaciones');

        // Guardar los cambios en la base de datos
        $solicitud->save();

        // Redirigir de vuelta a la página de la solicitud
        return view('admi.observationSent');
    }

    public function userUpdateRequest(Request $request, $id)
    {
        $solicitud = Solicitudes::findOrFail($id);
        abort_unless($solicitud->email === Auth::user()->email, 403);
        abort_unless(in_array((int) $solicitud->status, [0, 1], true), 422, 'La solicitud ya no admite cambios.');
        $requirements = EstanciaRequisitos::where('id_estancia', $solicitud->id_estancia)->firstOrFail();
        $ids = json_decode($requirements->id_requisitos, true) ?? [];
        $rules = $this->solicitudRules();
        foreach ($ids as $index => $requisitoId) {
            $rules['nuevo_archivo_'.$index] = 'nullable|file|mimes:pdf,doc,docx|max:2048';
        }
        $data = $request->validate($rules);
        $paths = array_pad(json_decode($solicitud->requisitos, true) ?? [], count($ids), null);
        $newPaths = [];
        $oldPaths = [];
        try {
            foreach ($ids as $index => $requisitoId) {
                if ($request->hasFile('nuevo_archivo_'.$index)) {
                    $file = $request->file('nuevo_archivo_'.$index);
                    $name = $requisitoId.'_'.$file->hashName();
                    $relative = 'solicitudes/'.$id.'/'.$name;
                    $file->move(public_path('solicitudes/'.$id), $name);
                    $newPaths[] = $relative;
                    $oldPaths[] = is_array($paths[$index]) ? ($paths[$index]['archivo'] ?? null) : $paths[$index];
                    $paths[$index] = $relative;
                }
            }
            $solicitud->forceFill(collect($data)->only(array_keys($this->solicitudRules()))->all());
            $solicitud->requisitos = json_encode($paths);
            $solicitud->save();
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) { \Illuminate\Support\Facades\File::delete(public_path($path)); }
            throw $exception;
        }
        foreach (array_filter($oldPaths) as $path) { \Illuminate\Support\Facades\File::delete(public_path($path)); }
        return redirect()->route('userSolicitudes')->with('success', 'Solicitud actualizada correctamente.');
    }

    public function VinculacionShowRequest($id){
        $solicitud=Solicitudes::findOrFail($id);
        $convenio=Convenio::where('nombre', $solicitud->empresa)->first();
        if($convenio){
            if($convenio->fecha_vigencia>Carbon::today()){
                $status='Vigente';
            }
            else{
                $status='No vigente';
            }
        }else{
            $status='Inexistente';
        }
        return view('vinculacion.showRequestVinculacion',compact('solicitud','status'));
    }
    public function validaConvenio($id){
        $solicitud=Solicitudes::findOrFail($id);
        $solicitud->status_convenio=2;//Convenio válido

        $solicitud->save();
        return redirect()->route('vinculacionDashboard')-> with('success','Solicitud respondida correctamente');
    }
    public function rechazaConvenio($id){
        $solicitud=Solicitudes::findOrFail($id);
        $solicitud->status_convenio=1;//Convenio Rechazado/ No válido
        $solicitud->status=3;//Solicitud rechazada

        $solicitud->save();
        return redirect()->route('vinculacionDashboard')-> with('success','Solicitud respondida correctamente');
    }
    public function convenioInexistente($id){
        $solicitud=Solicitudes::findOrFail($id);
        $solicitud->status_convenio=0; //Convenio inexistente
        $solicitud->status=0;//En revisión
        $solicitud->observaciones='Convenio inexistente, revisión en espera.';

        $solicitud->save();
        return redirect()->route('vinculacionDashboard')-> with('success','Solicitud respondida correctamente');
    }
    public function cancelRequest($id){
        $solicitud= Solicitudes::findOrFail($id);
        abort_unless($solicitud->email === Auth::user()->email, 403);

        if($solicitud){
            $solicitud->status=8;
            $solicitud->observaciones='Solicitud Cancelada';
            $solicitud->save();    
        }
        return redirect()->route('userSolicitudes')->with('success', 'Acción realizada correctamente');
    }
}