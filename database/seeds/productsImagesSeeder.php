<?php

use App\products;
use App\productsImages;
use Illuminate\Database\Seeder;

class productsImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = products::all();
        for($i= 1; $i < $products->count(); $i++){
            for($j = 1; $j < 4; $j++){
                productsImages::create([
                    'product_id' => $i,
                    'path' => 'image_' . $i . '_' . $j
                ]);
            }
        }
    }
}
