<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['totalPrice', 'user_id', 'transactionID', 'name', 'address'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item(){
        return $this->hasMany(OrderItems::class , 'order_id');
    }
}
