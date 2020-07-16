<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productsTranslation extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_translations';
}
