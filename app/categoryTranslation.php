<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoryTranslation extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories_translations';
}
