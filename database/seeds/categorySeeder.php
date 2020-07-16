<?php

use App\category;
use Illuminate\Database\Seeder;

class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 30; $i++) {
            $category = category::create();
            foreach (['en', 'ar'] as $locale) {
                $category->translateOrNew($locale)->name = "category_{$i}_{$locale}";
                $category->save();
            }
        }
    }
}
