<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    const RATE = "0,1,2,3,4,5";
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
