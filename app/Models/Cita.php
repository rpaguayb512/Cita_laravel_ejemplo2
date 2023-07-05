<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = ['hora', 'paciente_id'];



     public function paciente()
    {
        return $this->belongsTo('App\Models\Paciente', 'paciente_id', 'id');
    }



}
