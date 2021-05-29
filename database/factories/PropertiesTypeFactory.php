<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PropertiesType;
use Faker\Generator as Faker;

$factory->define(PropertiesType::class, function (Faker $faker) {
    return [
        'name' => $faker->state,
    ];
});
