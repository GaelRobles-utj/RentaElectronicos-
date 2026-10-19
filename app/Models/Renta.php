<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Renta extends Model
{
    use HasFactory;

    protected $table = 'rentas';

    protected $fillable = [
        'cliente_id', 'fecha_renta', 'fecha_devolucion', 'iva', 'descuento', 'deposito', 'total', 'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function productosRenta()
    {
        return $this->hasMany(ProductoRenta::class);
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }
}
