@extends('layouts.app')

@section('content')
    <h1>Listado de Pacientes</h1>

    <a href="{{ route('pacientes.create') }}" class="btn btn-primary mb-3">Crear Paciente</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <!-- Agrega aquí los encabezados de los demás campos -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pacientes as $paciente)
                <tr>
                    <td>{{ $paciente->id }}</td>
                    <td>{{ $paciente->nombre }}</td>
                    <td>{{ $paciente->apellido }}</td>
                    <!-- Agrega aquí las celdas de los demás campos -->
                    <td>
                        <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este paciente?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
