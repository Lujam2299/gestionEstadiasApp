<?php

namespace App\Http\Controllers;
use App\Models\Requisitos;
use App\Models\Estancia;
use App\Models\Estanciarequisitos;

use Illuminate\Http\Request;

class EstanciaController extends Controller
{
    public function indexUser()
    {
        $estancias = Estancia::all(); 

        return view('dashboard', compact('estancias')); 
    }
    public function index()
    {
        $estancias = Estancia::all(); 

        return view('admi.adminDashboard', compact('estancias')); 
    }
    //apartado para redirigir a vistas
    public function create()
    {
        $requisitos = Requisitos::select('id', 'nombre')->get();
        return view('admi.createEstancia', compact('requisitos'));
    }
    public function showSolicitudes()
    {
        $solicitudes = SolicitudesEstancias::all();
        return view('admi.solicitudes', ['solicitudes' => $solicitudes]);
    }
    public function historicoSolicitudes()
    {
        $estancias = Estancia::all();

        return view('admi.historicoSolicitudes', compact('estancias'));
    }

    //crear estancia nueva
    public function guardar(Request $request)
{
    // Validación de los campos de la estancia
    $request->validate([
        'nombre' => 'required|string',
        'fecha_convocatoria' => 'required|date',
        'fecha_cierre' => 'required|date',
        'periodo_duracion' => 'required|string',
        'archivo_convocatoria' => 'required|file|mimes:pdf',
    ]);

    // Crear una nueva instancia de Estancia y guardar los datos
    $estancia = new Estancia();
    $estancia->nombre = $request->nombre;
    $estancia->fecha_convocatoria = $request->fecha_convocatoria;
    $estancia->fecha_cierre = $request->fecha_cierre;
    $estancia->periodo_duracion = $request->periodo_duracion;

    // Guardar el archivo de convocatoria
    $archivoConvocatoria = $request->file('archivo_convocatoria');
    $nombreArchivo = $archivoConvocatoria->getClientOriginalName();
    $archivoConvocatoria->move(public_path('archivos'), $nombreArchivo);
    $estancia->archivo_convocatoria = 'archivos/' . $nombreArchivo;
    $estancia->save();

    // Obtener el ID recién creado de la estancia
    $idEstancia = $estancia->id;

    // Recopilar y guardar los requisitos seleccionados como JSON
    $requisitosSeleccionados = $request->requisitos;
    $requisitosJson = json_encode($requisitosSeleccionados);

    // Crear una nueva instancia de EstanciaRequisito y guardar los datos
    $estanciaRequisito = new Estanciarequisitos();
    $estanciaRequisito->id_estancia = $idEstancia; // Usar el ID de la estancia
    $estanciaRequisito->id_requisitos = $requisitosJson;
    $estanciaRequisito->save();

    // Redireccionar a la vista de éxito o a donde sea necesario
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

        return redirect()->route('admi.adminDashboard')->with('success', 'Estancia eliminada correctamente');
    }
    public function edit(Estancia $estancia)
    {
        $requisitos = Requisitos::all(); 
        return view('admi.estanciaEdit', compact('estancia','requisitos'));
    }
    public function update(Request $request, Estancia $estancia)
{
    // Validación de los campos de edición
    $request->validate([
        'nombre' => 'required|string',
        'fecha_convocatoria' => 'required|date',
        'fecha_cierre' => 'required|date',
        'periodo_duracion' => 'required|string',
        'requisitos' => 'required|array', // Asegúrate de que los requisitos sean un array
        'requisitos.*' => 'exists:requisitos,id', // Asegúrate de que los requisitos existan en la tabla de requisitos
    ]);

    // Actualiza los campos de la estancia con los datos del formulario
    $estancia->update([
        'nombre' => $request->nombre,
        'fecha_convocatoria' => $request->fecha_convocatoria,
        'fecha_cierre' => $request->fecha_cierre,
        'periodo_duracion' => $request->periodo_duracion,
        // Actualiza los demás campos según corresponda
    ]);

    // Actualiza los requisitos de la estancia
    $estancia->requisitos()->sync($request->requisitos);

    // Redirecciona a la página de detalles de la estancia actualizada
    return redirect()->route('admi.adminDashboard')->with('success', 'Estancia actualizada exitosamente.');
}

    public function showUserEstancia($id)
    {
        $estancia = Estancia::findOrFail($id);
        return view('user.showUserEstancia', compact('estancia'));
    }
}
