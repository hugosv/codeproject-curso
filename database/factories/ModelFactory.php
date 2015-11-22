<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(CodeProject\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(CodeProject\Entities\Client::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'responsible' => $faker->name,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'obs' => $faker->sentence,
    ];
});

$factory->define(CodeProject\Entities\Project::class, function (Faker\Generator $faker) {
    return [
        'owner_id' => rand(1,10),
        'client_id' => rand(1,10),
        'name' => $faker->word,
        'description' => $faker->sentence,
        'progress' => rand(1,100),
        'status' => rand(1,3),
        'due_date' => $faker->dateTimeBetween($startDate = '+5 days', $endDate = '+2 years'),
    ];
});

$factory->define(CodeProject\Entities\ProjectNote::class, function (Faker\Generator $faker) {
    return [
        'project_id' => rand(1,10),
        'title' => $faker->sentence($nbWords = 4),
        'note' => $faker->paragraph(),
    ];
});

$factory->define(CodeProject\Entities\ProjectMember::class, function (Faker\Generator $faker) {
    return [
        'project_id' => $faker->numberBetween($min = 1, $max = 10),
        'member_id' => $faker->numberBetween($min = 1, $max = 11),
    ];
});

$factory->define(CodeProject\Entities\ProjectTask::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence($nb = 3),
        'project_id' => rand(1,10),
        'start_date' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = '-1 years'),
        'due_date' => $faker->dateTimeBetween($startDate = '+5 days', $endDate = '+2 years'),
        'status' => rand(1,3),
    ];
});

$factory->define(CodeProject\Entities\OAuthClient::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->word,
        'secret' => $faker->word,
        'name' => $faker->company,
    ];
});
