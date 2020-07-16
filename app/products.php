<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use Translatable;
    public $translatedAttributes = ['name', 'description'];

    public const PAYMENT = true;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avaiable', 'price_after_discount'];


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function images()
    {
        return $this->hasMany(productsImages::class, 'product_id');
    }
    public function specification()
    {
        return $this->hasMany(Specification::class, 'product_id');
    }
    public function review()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    public function trsnalations()
    {
        return $this->hasMany(productsTranslation::class);
    }
    public function scopePrice(Builder $query, $min, $max): Builder
    {
        return $query->where('price', '>=', $min)->where('price', '<=', $max);
    }
    public function getAvaiableAttribute()
    {
        if ($this->quantity > 0) {
            return true;
        }
        return false;
    }
    public function getPriceAfterDiscountAttribute()
    {
        if ($this->discount == 0) {
            return $this->price;
        }
        return number_format($this->price - ($this->price * ($this->discount / 100)), 3);
    }
    public function canBuy($quantity)
    {
        if ((int) $quantity <= $this->quantity && ($this->avaiable && $this->quantity <=  $this->type->quantity)) {
            return true;
        }
        return false;
        //return $this->avaiable && $this->quantity <=  $this->type->quantity;
    }
}
