<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'tipo_id', 'marca_id', 'categoria_id', 'nombre', 'descripcion', 'precio_dia', 'existencia', 'descuento', 'imagen1', 'imagen2', 'imagen3', 'estado'
    ];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function productosRenta()
    {
        return $this->hasMany(ProductoRenta::class);
    }
}
