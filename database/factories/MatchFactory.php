<?php

use App\Match;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Match::class, function (Faker $faker) {
    return [
        'title' => $faker->chars(15, true),
        'expired_at' => $faker->dateTimeBetween('now', '+15 days'),
        'status' => $faker->randomElement(['open', 'close']),
    ];
});
