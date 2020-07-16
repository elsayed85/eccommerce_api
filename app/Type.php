<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use Translatable;
    public $translatedAttributes = ['name'];
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
    protected $appends = ['avaiable'];
    public function category()
    {
        return $this->belongsTo(category::class);
    }
    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_types');
    }
    public function product()
    {
        return $this->hasMany(products::class);
    }
    public function brand(){
        return $this->belongsToMany(Brand::class , 'brand_types');
    }
    public function getAvaiableAttribute()
    {
        if ($this->quantity > 0) {
            return true;
        }
        return false;
    }
}
