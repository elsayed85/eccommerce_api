<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use Translatable;
    public $translatedAttributes = ['name'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public function type()
    {
        return $this->hasMany(Type::class , 'category_id');
    }
    public function trsnalations()
    {
        return $this->hasMany(categoryTranslation::class);
    }
}
