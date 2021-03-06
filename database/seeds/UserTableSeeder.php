<?php

use CodeProject\Entities\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Hugo Vieira',
            'email' => 'hugo@example.com',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(User::class, 10)->create();
    }
}
