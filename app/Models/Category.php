<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categorias'; // Asegúrate de que este sea el nombre correcto de tu tabla

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'categoria_id', 'nombre');
    }
}
