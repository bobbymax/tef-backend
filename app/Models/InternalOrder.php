<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalOrder extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
