<?php

/** @var Factory $factory */

use App\Shop\Province\Province;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Province::class, function (Faker $faker) {
    return [
        'nome' => $faker->city,
        'country_id' => 1
    ];
});
