<?php

use App\Admin;
use App\Ads;
use App\Attribute;
use App\AttributeType;
use App\Brand;
use App\BrandType;
use App\category;
use App\Order;
use App\products;
use App\productsImages;
use App\Type;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Admin::truncate();
        Brand::truncate();
        category::truncate();
        Type::truncate();
        Brand::truncate();
        Attribute::truncate();
        products::truncate();
        productsImages::truncate();
        User::truncate();
        Order::truncate();
        User::create([
            'email' => 'test@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone' => '01092291556'
        ]);
        Ads::truncate();
        $this->call(usersSeeder::class);
        $this->call(adminSeeder::class);
        $this->call(categorySeeder::class);
        $this->call(typesSeeder::class);
        $this->call(BrandsSeeder::class);
        $this->call(attributeSeeder::class);
        $this->call(productsSeeder::class);
        $this->call(productsImagesSeeder::class);
        $this->call(AdsSeeder::class);
    }
}
