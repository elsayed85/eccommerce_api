<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wishlist extends Model
{
    protected $fillable = ['id', 'user_id'];
    public $incrementing = false;

    public function items()
    {
        return $this->hasMany(wishlistItems::class, 'wishlist_id');
    }
}
