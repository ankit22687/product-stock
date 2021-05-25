<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'code' => $faker->randomNumber,
        'name' => $faker->unique()->name,
        'description' => $faker->realText($maxNbChars = 200),
    ];
});
