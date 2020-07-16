<?php

use App\products;
use App\Type;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    // 'name' => 'product_' . rand(1, 700),
    // 'description' => $faker->paragraph(3),

    public function run()
    {
        factory(products::class, 50)->create()->each(function ($product) {
            foreach (['en', 'ar'] as $locale) {
                $product->translateOrNew($locale)->name = "product{$product->id}_name_{$locale}";
                $product->translateOrNew($locale)->description = "product{$product->id}_description_{$locale}";
                $product->save();
            }
            $type = $product->type;
            if ($type->attribute->count() > 0) {
                $product->specification()->create([
                    'attribute_id' => $type->attribute->random()->id,
                    'value' => 'text here'
                ]);
            }
        });
    }
}
