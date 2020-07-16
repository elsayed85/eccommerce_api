<?php

use App\Brand;
use App\Type;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 50; $i++) {
            $brand = Brand::create();
            foreach (['en', 'ar'] as $locale) {
                $brand->translateOrNew($locale)->name = "brand_{$i}_{$locale}";
                $brand->save();
            }
        }
        Brand::all()->map(function ($brand) {
            return $brand->type()->sync(
                Type::all()->random()->id
            );
        });
    }
}
