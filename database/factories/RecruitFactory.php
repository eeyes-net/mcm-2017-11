<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Recruit::class, function (Faker $faker) {
    return [
        'tags' => implode(',', $faker->words(2)),
        'members' => $faker->name . $faker->name,
        'description' => $faker->paragraph,
        'contact' => $faker->phoneNumber,
    ];
});
