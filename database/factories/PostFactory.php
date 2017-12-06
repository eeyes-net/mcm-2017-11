<?php

use App\Post;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->realText('24'),
        'content' => $faker->realText(),
    ];
});
