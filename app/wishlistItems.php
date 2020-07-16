<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class wishlistItems extends Model
{
    protected $fillable = ['product_id', 'wishlist_id'];
    protected $primaryKey = ['wishlist_id', 'product_id'];
    public $incrementing = false;
    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query->where('wishlist_id', $this->getAttribute('wishlist_id'))
            ->where('product_id', $this->getAttribute('product_id'));
    }
    public function wishlist()
    {
        return $this->belongsTo(wishlist::class);
    }

    public function product()
    {
        return $this->hasOne(products::class);
    }
}
