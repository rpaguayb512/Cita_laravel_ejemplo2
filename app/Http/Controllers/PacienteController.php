<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;


class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::all();
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $paciente = new Paciente();
        $paciente->nombre = $request->input('nombre');
        $paciente->apellido = $request->input('apellido');
        // Asigna aquí los valores de los demás campos
        $paciente->save();

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado correctamente.');
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->nombre = $request->input('nombre');
        $paciente->apellido = $request->input('apellido');
        // Asigna aquí los valores de los demás campos
        $paciente->save();

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }
}
