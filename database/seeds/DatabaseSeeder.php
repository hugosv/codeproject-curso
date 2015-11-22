<?php

use CodeProject\Entities\Client;
use CodeProject\Entities\OAuthClient;
use CodeProject\Entities\Project;
use CodeProject\Entities\ProjectFile;
use CodeProject\Entities\ProjectMember;
use CodeProject\Entities\ProjectNote;
use CodeProject\Entities\ProjectTask;
use CodeProject\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::statement('SET foreign_key_checks = 0');

        ProjectNote::truncate();
        ProjectTask::truncate();
        ProjectMember::truncate();
        ProjectFile::truncate();
        Project::truncate();
        Client::truncate();
        User::truncate();
        OAuthClient::truncate();

        DB::statement('SET foreign_key_checks = 1');

        $this->call(UserTableSeeder::class);
        $this->call(ClientTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(ProjectNoteTableSeeder::class);
        $this->call(ProjectMemberTableSeeder::class);
        $this->call(ProjectTaskTableSeeder::class);
        $this->call(OAuthClientTableSeeder::class);

        Model::reguard();
    }
}
