<?php

use App\Attribute;
use App\Type;
use Illuminate\Database\Seeder;

class attributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 70; $i++){
            $Attribute = Attribute::create();
            foreach (['en', 'ar'] as $locale) {
                $Attribute->translateOrNew($locale)->name = "Attribute_{$i}_{$locale}";
                $Attribute->save();
            }
        }
        Attribute::all()->map(function ($attr) {
            return $attr->type()->sync(
                Type::all()->random()->id
            );
        });
        // // type.attr
        // Attribute::create([
        //     'name' => 'attr1.1'
        // ]);
        // Attribute::create([
        //     'name' => 'attr1.2'
        // ]);
        // Attribute::create([
        //     'name' => 'attr1.3'
        // ]);


        // Attribute::create([
        //     'name' => 'attr2.1'
        // ]);
        // Attribute::create([
        //     'name' => 'attr2.2'
        // ]);
        // Attribute::create([
        //     'name' => 'attr2.3'
        // ]);
        // Attribute::create([
        //     'name' => 'attr2.4'
        // ]);


        // Attribute::create([
        //     'name' => 'attr3.1'
        // ]);
        // Attribute::create([
        //     'name' => 'attr3.2'
        // ]);
    }
}
