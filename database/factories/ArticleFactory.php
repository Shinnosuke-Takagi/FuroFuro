<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'id' => Str::random(12),
        'title' => $faker->title,
        'body' => $faker->text,
        'map_query' => $faker->city,
        'main_filename' => Str::random(12). '.jpg',
        'user_id' => fn() => factory(User::class)->create()->id,
    ];
});
