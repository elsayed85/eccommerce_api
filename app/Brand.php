<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Translatable;
    public $translatedAttributes = ['name'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public function products()
    {
        return $this->hasMany(products::class);
    }
    public function type()
    {
        return $this->belongsToMany(Type::class, 'brand_types');
    }
}
