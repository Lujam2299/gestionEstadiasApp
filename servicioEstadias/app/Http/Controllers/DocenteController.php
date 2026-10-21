<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Estancia;
use App\Models\Informe;
use App\Models\Solicitudes;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function edit($id)
    {
        $docente = User::findOrFail($id);
        return view('admi.docenteEdit', ['docente' => $docente]);
    }

    public function update(Request $request, $id)
    {
        $docentes = User::where('id', '<>', 1)->paginate(10);
        $docente =User::findOrFail($id);
        
        // Actualizar los campos del docente
        //$docente->name = $request->input('name');
        //$docente->email = $request->input('email');
        $docente->rfc = $request->input('rfc');
        $docente->nombramiento = $request->input('nombramiento');
        //$docente->status = $request->input('status');
        $docente->curp = $request->input('curp');
        
        // Verificar si se proporcionó una nueva contraseña
        if ($request->filled('new_password')) {
            // Obtener la nueva contraseña del request
            $newPassword = $request->input('new_password');
            // Cifrar la nueva contraseña
            $hashedPassword = Hash::make($newPassword);
            // Guardar la nueva contraseña cifrada en el modelo
            $docente->password = $hashedPassword;
        }
        
        // Guardar los cambios en la base de datos
        $docente->save();
        $estancias = Estancia::all(); 
        return redirect()->route('docente.edit', $docente->id)->with('success', 'Los cambios se guardaron correctamente.');

        // Redirigir a alguna vista después de guardar los cambios
    }
    public function verArchivos($id, $sol){
        $estancia = Estancia::findOrFail($id);
        $solicitud = Solicitudes::findOrFail($sol);
        abort_unless($solicitud->email === Auth::user()->email, 403);
        abort_unless((int) $solicitud->id_estancia === (int) $estancia->id, 404);
        return view('user.informesView',compact('estancia', 'solicitud'));
    }

    public function generarInforme($id)
    {
        $docente = Auth::user();
        $estancia = Estancia::findOrFail($id);
        $html = view('user.informe', compact('docente', 'estancia'))->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->render();
        return $dompdf->stream('carta_presentacion.pdf');
    }

    public function enableInformes(){
        $constancia='';
        $informe_final='';
        $email = Auth::user()->email; 
        $solicitud = Solicitudes::where('email', $email)
                                ->whereIn('status', [2, 5])
                                ->latest()
                                ->first();

        // Determinar si los campos deben estar habilitados
        $camposHabilitados = $solicitud && $solicitud->status != 4;
        if ($solicitud && $solicitud->status == 5) {
            $informe = Informe::where('id_solicitud', $solicitud->id)->first(); // Obtener informe
            if ($informe) {
                $constancia = $informe->ruta_constancia; // Acceder a la propiedad
                $informe_final = $informe->ruta_oficio; // Acceder a la propiedad
            }
        }

        return view('user.enableInformes', compact('solicitud', 'camposHabilitados', 'constancia', 'informe_final'));

    }

    public function destroy($id)
    {
        $estancias = Estancia::all();
        $docente = User::findOrFail($id);
        $docente->delete();

        return view('admi.adminDashboard',compact('estancias'))->with('success', 'Docente eliminado correctamente.');
    }

    public function create()
    {
        return view('admi.docenteCreate');
    }

    // Guardar un nuevo docente
    public function store(Request $request)
    {
        $estancias=Estancia::all();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:docentes,email',
            'rfc' => 'required|string|max:13',
            'nombramiento' => 'required|string|max:255',
            'academia' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'rfc' => $request->rfc,
            'nombramiento' => $request->nombramiento,
            'academia' => $request->academia,
            'password' => Hash::make($request->password), 
        ]);

        return view('admi.adminDashboard', compact('estancias'))
                         ->with('success', 'Docente registrado exitosamente.');
    }

    public function descargarCarta($id)
    {
        Carbon::setLocale('es');
        $solicitud = Solicitudes::findOrFail($id);
        abort_unless($solicitud->email === Auth::user()->email, 403);

        $departamento=auth()->user()->academia;
        $fecha = now()->translatedFormat('d \d\e F \d\e Y');
        $inicioEstancia = Carbon::parse($solicitud->inicio_estancia)->translatedFormat('d \d\e F \d\e Y');
        $finEstancia = Carbon::parse($solicitud->fin_estancia)->translatedFormat('d \d\e F \d\e Y');
        $datos = [
            'docente' => $solicitud->docente,
            'empresa' => $solicitud->empresa,
            'fecha' => $fecha,
            'titular' => $solicitud->titular_empresa,
            'cargo' => $solicitud->puesto_titular,
            'area' => $solicitud->area_complementacion,
            'objetivo' => $solicitud->objetivo,
            'inicio' => $inicioEstancia,
            'fin' => $finEstancia,
            'departamento' => $departamento,
        ];

        $pdf = Pdf::loadView('plantillas.carta_presentacion', $datos);
        return $pdf->download('carta_presentacion.pdf');
    }

    public function descargarOficio($id)
    {
        Carbon::setLocale('es');
        $solicitud = Solicitudes::findOrFail($id);
        abort_unless($solicitud->email === Auth::user()->email, 403);

        $fecha = now()->translatedFormat('d \d\e F \d\e Y');
        $inicioEstancia = Carbon::parse($solicitud->inicio_estancia)->translatedFormat('d \d\e F \d\e Y');
        $finEstancia = Carbon::parse($solicitud->fin_estancia)->translatedFormat('d \d\e F \d\e Y');
        $datos = [
            'fecha' => $fecha,
            'nombre' => $solicitud->docente,
            'lugar' => $solicitud->empresa,
            'asunto' => $solicitud->proyecto,
            'dias' => $inicioEstancia . ' a ' . $finEstancia,
            'viaticos' => 'No',
        ];

        $pdf = Pdf::loadView('plantillas.oficio_comision', $datos);
        return $pdf->download('oficio_comision.pdf');
    }
}
