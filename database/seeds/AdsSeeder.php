<?php

use App\Ads;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ads::create([
            'path' => 'https://hackernoon.com/hn-images/1*j41hMsYft-ifSvXuWOb7Gg.png'
        ]);
        Ads::create([
            'path' => 'https://hackernoon.com/hn-images/1*lEyU2zS2TBaOl_fbXqPChA.png'
        ]);
        Ads::create([
            'path' => 'https://miro.medium.com/max/3840/1*9KQiMgdJk8KHsUNghSz9Yg.png'
        ]);
        Ads::create([
            'path' => 'https://www.skytecsolution.ca/wp-content/uploads/2018/07/best-mobile-app-development-company-in-toronto1.png'
        ]);
    }
}
