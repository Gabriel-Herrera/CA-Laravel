<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'productos';

    protected $casts = [
        'etiquetas' => 'array',
    ];
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id',
        'imagen',
        'descuento',
        'etiquetas',
    ];



    public function setEtiquetasAttribute($value)
    {
        $this->attributes['etiquetas'] = json_encode($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function getPrecioFinalAttribute()
    {
        return $this->precio * (1 - ($this->descuento / 100));
    }

    public function getPrecioFormateadoAttribute()
    {
        return number_format($this->precio_final, 0, ',', '.') . ' CLP';
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'producto_id');
    }
}
