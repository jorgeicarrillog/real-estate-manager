<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Citie;
use Faker\Generator as Faker;

$factory->define(Citie::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
    ];
});
