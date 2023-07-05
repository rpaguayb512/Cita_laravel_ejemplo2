@extends('layouts.app')

@section('content')
    <h1>Editar Paciente</h1>

    <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $paciente->nombre }}">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="{{ $paciente->apellido }}">
        </div>

        <!-- Agrega aquí los campos adicionales del formulario -->

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
@endsection
