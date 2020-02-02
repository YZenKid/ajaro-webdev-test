<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create([
            'name' => 'jarjit',
            'email' => 'jarjit@mail.com',
            'password' => Hash::make('123456'),
            'api_token' => Str::random(60),
        ]);
    }
}
