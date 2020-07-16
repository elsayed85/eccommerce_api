<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Brand;
use App\products;
use App\Type;
use Faker\Generator as Faker;

$factory->define(products::class, function (Faker $faker) {
    return [
        'quantity' => $faker->randomNumber(2),
        'brand_id' => Brand::all()->random()->id,
        'type_id' => Type::all()->random()->id,
        'price' => rand(10,400),
        'discount' => $faker->randomNumber(2)
    ];
});
