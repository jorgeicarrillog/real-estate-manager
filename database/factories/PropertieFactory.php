<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Propertie;
use Faker\Generator as Faker;

$factory->define(Propertie::class, function (Faker $faker) {
    return [
        "title" => $faker->company,
        "description" => $faker->text(200),
        "address" => $faker->streetAddress,
        "longitude" => $faker->longitude(-180, 180),
        "latitude" => $faker->latitude(-90, 90),
        "area" => $faker->numberBetween(10, 900),
        "bedrooms" => $faker->randomDigit,
        "toilets" => $faker->randomDigit,
        "floor" => $faker->randomDigit,
        "value" => $faker->numberBetween(100000, 900000000),
    ];
});
