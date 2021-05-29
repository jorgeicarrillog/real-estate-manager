<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Countrie;
use Faker\Generator as Faker;

$factory->define(Countrie::class, function (Faker $faker) {
    return [
        'name' => $faker->country,
    ];
});
