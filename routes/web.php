<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/citas', 'CitaController@index')->name('citas');
Route::get('/', function () { return view('welcome'); });
Route::post('/asignar-cita', 'CitaController@asignarCita')->name('asignar.cita');
Route::get('/pacientes/disponibles/{id?}', 'CitaController@obtenerPacientesDisponibles')->name('pacientes.disponibles');
Route::resource('pacientes', 'PacienteController');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
