<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{

    public function index()
    {
        // Método de ejemplo para mostrar una vista de listado de citas
        $citas = Cita::all();
        return view('cita')->with('citas', $citas);
        
    }

    public function asignarCita(Request $request)
    {
        // Obtener los datos enviados en la solicitud
        $hora = $request->input('hora');
        $pacienteId = $request->input('paciente_id');

        // Verificar si el paciente existe
        $paciente = Paciente::find($pacienteId);
        if (!$paciente) {
            return response()->json(['error' => 'El paciente no existe'], 404);
        }

        // Verificar si ya hay una cita asignada a esa hora
        $citaExistente = Cita::where('hora', $hora)->first();
        if ($citaExistente) {
            return response()->json(['error' => 'Ya existe una cita asignada a esa hora'], 400);
        }

        // Crear la nueva cita
        $cita = new Cita();
        $cita->hora = $hora;
        $cita->paciente_id = $pacienteId;
        $cita->save();

        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Cita asignada correctamente'], 200);
    }

    public function obtenerPacientesDisponibles($hora)
    {
        // Obtener los pacientes disponibles para la hora especificada
        $pacientesDisponibles = Paciente::all();

        // Devolver una respuesta en formato JSON con los nombres de los pacientes disponibles
        return response()->json($pacientesDisponibles);
    }



    public function  destroy(Request $request)
    {
        // Obtener los datos enviados en la solicitud
        $hora = $request->input('id');

        $cita = Cita::where('hora', '=', $hora);
        
        $cita->delete();
         return redirect()->action('CitaController@index');
        
      
    }








}

