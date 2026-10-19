<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombres', 'apellidos', 'correo', 'contrasena', 'telefono', 'direccion', 'imagen', 'estado'
    ];

    public function rentas()
    {
        return $this->hasMany(Renta::class);
    }
}
