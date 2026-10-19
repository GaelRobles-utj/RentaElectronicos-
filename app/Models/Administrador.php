<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrador extends Model
{
    use HasFactory;

    protected $table = 'administradores';

    protected $fillable = [
        'nombres', 'apellidos', 'correo', 'usuario', 'contrasena', 'imagen', 'rol', 'estado'
    ];

}
