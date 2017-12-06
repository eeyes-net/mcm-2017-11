<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => 'test_user_' . $faker->unique()->userName,
        'stu_id' => $faker->numberBetween(0, 5) . $faker->randomNumber(9, true),
        'name' => $faker->name,
        'department' => $faker->words(2, true),
        'major' => $faker->word,
        'class' => $faker->word . $faker->numberBetween(10, 999),
        'contact' => $faker->phoneNumber,
        'email' => $faker->email,
        'password' => '*',
        'group' => 'student',
    ];
});
