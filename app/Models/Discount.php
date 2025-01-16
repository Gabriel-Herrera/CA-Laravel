<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'starts_at',
        'expires_at',
        'uses',
        'max_uses',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        return $this->is_active &&
               (!$this->starts_at || $this->starts_at->isPast()) &&
               (!$this->expires_at || $this->expires_at->isFuture()) &&
               (!$this->max_uses || $this->uses < $this->max_uses);
    }

    public function apply($amount)
    {
        if ($this->type === 'percentage') {
            return $amount * (1 - $this->value / 100);
        } else {
            return max($amount - $this->value, 0);
        }
    }
}

