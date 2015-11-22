<?php

use CodeProject\Entities\OAuthClient;
use Illuminate\Database\Seeder;

class OAuthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(OAuthClient::class)->create([
            'id' => 'appid1',
            'secret' => 'secret',
            'name' => 'CodeProject',
        ]);

    }
}
