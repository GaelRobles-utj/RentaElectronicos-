<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductoRenta extends Model
{
    use HasFactory;

    protected $table = 'productos_renta';

    protected $fillable = [
        'renta_id', 'producto_id', 'cantidad', 'precio_dia', 'dias', 'descuento', 'subtotal', 'estado'
    ];

    public function renta()
    {
        return $this->belongsTo(Renta::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
