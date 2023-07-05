@extends('layouts.app')

@section('content')
    <h1>Crear Paciente</h1>

    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control">
        </div>

        <!-- Agrega aquí los campos adicionales del formulario -->

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection
