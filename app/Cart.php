<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['id', 'content', 'user_id'];
    public $incrementing = false;

    public function items()
    {
        return $this->hasMany(CartItem::class, 'Cart_id');
    }
}
