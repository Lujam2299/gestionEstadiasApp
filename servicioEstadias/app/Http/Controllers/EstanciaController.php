<?php

namespace App\Http\Controllers;

use App\Models\Estancia;
use App\Models\Estanciarequisitos;
use App\Models\Requisitos;
use App\Models\Solicitudes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstanciaController extends Controller
{
    public function indexUser()
    {
        $estancias = Estancia::orderBy('fecha_convocatoria', 'asc')->get();

        $solicitudes = Solicitudes::where('email', auth()->user()->email)->get();

        foreach ($solicitudes as $solicitud) {
            if ($solicitud->status == 0 || $solicitud->status == 1) {
                $estancia = Estancia::find($solicitud->id_estancia);

                if ($estancia && $estancia->fecha_cierre < today()) {
                    $solicitud->status = 3;
                    $solicitud->observaciones = 'Plazo terminado, proceso incompleto';
                    $solicitud->save();
                }
            }
        }

        return view('dashboard', compact('estancias', 'solicitudes'));
    }

    public function index()
    {
        $estancias = Estancia::orderBy('fecha_convocatoria', 'asc')->get();
        foreach ($estancias as $estancia) {
            if ($estancia->fecha_cierre < today()) {
                $estancia->vigente = 1;
                $estancia->save();
            }
        }

        return view('admi.adminDashboard', compact('estancias'));
    }

    public function indexVinculacion()
    {
        $estancias = Solicitudes::where('status', 0)->get();

        return view('vinculacion.vinculacionDashboard', compact('estancias'));
    }

    //apartado para redirigir a vistas
    public function create()
    {
        $requisitos = Requisitos::select('id', 'nombre')->get();

        return view('admi.createEstancia', compact('requisitos'));
    }

    public function showSolicitudes()
    {
        $solicitudes = Solicitudes::all();

        return view('admi.solicitudes', ['solicitudes' => $solicitudes]);
    }

    public function historicoSolicitudes()
    {
        $estancias = Estancia::all();

        return view('admi.historicoSolicitudes', compact('estancias'));
    }

    public function historicoEstancias()
    {
        $estancias = Estancia::all(); // Obtener todos los registros de la tabla 'estancias'

        return view('admi.historicoEstancias', ['estancias' => $estancias]);
    }

    //crear estancia nueva
    public function guardar(Request $request)
    {
        // Validación de los campos de la estancia
        $request->validate([
            'nombre' => 'required|string',
            'empresa' => 'required|string',
            'fecha_convocatoria' => 'required|date',
            'fecha_cierre' => 'required|date|after_or_equal:fecha_convocatoria',
            'archivo_convocatoria' => 'required|file|mimes:pdf|max:2048',
            'requisitos' => 'required|array|min:1',
            'requisitos.*' => 'required|integer|distinct|exists:requisitos,id',
        ]);

        // Crear una nueva instancia de Estancia y guardar los datos (sin el archivo)
        $estancia = new Estancia();
        $estancia->nombre = $request->nombre;
        $estancia->empresa = $request->empresa;
        $estancia->fecha_convocatoria = $request->fecha_convocatoria;
        $estancia->fecha_cierre = $request->fecha_cierre;
        $estancia->archivo_convocatoria = ''; // Inicializar campo vacío
        $estancia->save();

        // Obtener el ID recién creado de la estancia
        $idEstancia = $estancia->id;

        // Guardar el archivo de convocatoria usando el ID de la estancia
        $archivoConvocatoria = $request->file('archivo_convocatoria');
        $nombreArchivo = $archivoConvocatoria->getClientOriginalName();
        $rutaArchivo = 'convocatorias/'.$idEstancia.'/'.$nombreArchivo;

        // Crear la carpeta si no existe
        if (! file_exists(public_path('convocatorias/'.$idEstancia))) {
            mkdir(public_path('convocatorias/'.$idEstancia), 0777, true);
        }

        // Mover el archivo a la carpeta correspondiente
        $archivoConvocatoria->move(public_path('convocatorias/'.$idEstancia), $nombreArchivo);

        // Actualizar la estancia con la ruta del archivo
        $estancia->archivo_convocatoria = $rutaArchivo;
        $estancia->save();

        // Recopilar y guardar los requisitos seleccionados como JSON
        $requisitosSeleccionados = $request->requisitos;
        $requisitosJson = json_encode($requisitosSeleccionados);

        // Crear una nueva instancia de EstanciaRequisitos y guardar los datos
        $estanciaRequisito = new EstanciaRequisitos();
        $estanciaRequisito->id_estancia = $idEstancia;
        $estanciaRequisito->id_requisitos = $requisitosJson;
        $estanciaRequisito->save();

        // Redireccionar a la vista de éxito
        return view('admi.successCreate');
    }

    public function showEstancia($id)
    {
        $estancia = Estancia::findOrFail($id);

        return view('admi.showEstancia', compact('estancia'));
    }

    public function eliminar($id)
    {
        $estancia = Estancia::findOrFail($id);
        $estancia->vigente = 1;
        //$estancia->delete();
        $estancia->save();

        return redirect()->route('adminDashboard')->with('success', 'Estancia eliminada correctamente');
    }

    public function edit(Estancia $estancia)
    {
        $requisitos = Requisitos::all();

        return view('admi.estanciaEdit', compact('estancia', 'requisitos'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_convocatoria' => 'required|date',
            'fecha_cierre' => 'required|date|after_or_equal:fecha_convocatoria',
            'archivo_convocatoria' => 'nullable|file|mimes:pdf|max:2048',
            'requisitos' => 'required|array',
            'requisitos.*' => 'integer|exists:requisitos,id',
        ]);

        // Buscar la estancia por su ID
        $estancia = Estancia::findOrFail($id);

        // Actualizar los campos con los nuevos valores del formulario
        $estancia->nombre = $request->input('nombre');
        $estancia->fecha_convocatoria = $request->input('fecha_convocatoria');
        $estancia->fecha_cierre = $request->input('fecha_cierre');

        // Procesar el archivo de convocatoria si se ha subido uno nuevo
        if ($request->hasFile('archivo_convocatoria')) {
            $archivo = $request->file('archivo_convocatoria');
            $nombreArchivo = $archivo->getClientOriginalName();
            $rutaArchivo = 'convocatorias/'.$id.'/'.$nombreArchivo;

            // Eliminar el archivo anterior si existe
            if ($estancia->archivo_convocatoria && file_exists(public_path($estancia->archivo_convocatoria))) {
                unlink(public_path($estancia->archivo_convocatoria));
            }

            // Crear la carpeta si no existe
            if (! file_exists(public_path('convocatorias/'.$id))) {
                mkdir(public_path('convocatorias/'.$id), 0777, true);
            }

            // Mover el nuevo archivo a la carpeta correspondiente
            $archivo->move(public_path('convocatorias/'.$id), $nombreArchivo);

            // Actualizar la ruta del archivo en la estancia
            $estancia->archivo_convocatoria = $rutaArchivo;
        }

        // Guardar los cambios de la estancia
        $estancia->save();

        // Actualizar los requisitos de la estancia
        $estanciaRequisitos = EstanciaRequisitos::firstOrNew(['id_estancia' => $id]);
        $estanciaRequisitos->id_requisitos = json_encode($request->requisitos);
        $estanciaRequisitos->save();

        // Redirigir a la vista de éxito o a donde sea necesario
        return redirect()->route('adminDashboard')->with('success', 'Estancia actualizada correctamente.');
    }

    public function showUserEstancia($id)
    {
        $nombreUsuario = Auth::user()->email;

        $solicitudesPendientes = Solicitudes::where('email', $nombreUsuario)
            ->where(function ($query) {
                $query->where('status', 0)
                    ->orWhere('status', 1)
                    ->orWhere('status', 2);
            })
            ->exists();

        $estancia = Estancia::findOrFail($id);

        return view('user.showUserEstancia', compact('estancia'), [
            'solicitudesPendientes' => $solicitudesPendientes,
        ]);
    }

    public function showUsers()
    {
        $docentes = User::where('id', '<>', 1)->paginate(10);

        return view('admi.showUsers', ['docentes' => $docentes]);
    }
}
