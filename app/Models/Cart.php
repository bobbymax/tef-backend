<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function internalOrder()
    {
        return $this->hasOne(InternalOrder::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
