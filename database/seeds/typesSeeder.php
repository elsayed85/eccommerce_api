<?php

use App\category;
use App\Type;
use Illuminate\Database\Seeder;

class typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 70; $i++){
            $type = Type::create([
                'category_id' => category::all()->random()->id,
                'quantity' => rand(10 , 600)
            ]);
            foreach (['en', 'ar'] as $locale) {
                $type->translateOrNew($locale)->name = "type_{$i}_{$locale}";
                $type->save();
            }
        }
    }
}
