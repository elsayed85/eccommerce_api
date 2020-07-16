<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productsImages extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function getPathAttribute($path)
    {
        return asset('app/public/' . $path);
    }
}
