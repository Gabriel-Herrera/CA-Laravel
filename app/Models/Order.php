<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'pedidos';
    
    protected $fillable = [
        'user_id',
        'fecha',
        'estado',
        'total'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'pedido_id');
    }
}