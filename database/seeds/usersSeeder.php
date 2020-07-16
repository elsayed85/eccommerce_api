<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'user1@website.com',
            'password' => Hash::make('password'),
            'phone' => '0109229557'
        ]);
        User::create([
            'email' => 'user2@website.com',
            'password' => Hash::make('password'),
            'phone' => '0109229558'
        ]);
        User::create([
            'email' => 'user3@website.com',
            'password' => Hash::make('password'),
            'phone' => '0109229559'
        ]);
        User::create([
            'email' => 'user4@website.com',
            'password' => Hash::make('password'),
            'phone' => '0109229510'
        ]);
    }
}
