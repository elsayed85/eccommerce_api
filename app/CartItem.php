<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $primaryKey = ['cart_id', 'product_id'];
    public $incrementing = false;
    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query->where('cart_id', $this->getAttribute('cart_id'))
            ->where('product_id', $this->getAttribute('product_id'));
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->hasOne(products::class);
    }
}
