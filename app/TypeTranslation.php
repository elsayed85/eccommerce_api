<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeTranslation extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type_translations';
}
