<?php

/** @var Factory $factory */

use App\Shop\Country\Country;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->country,
        'iso' => $faker->unique()->countryISOAlpha3,
        'iso3' => $faker->unique()->countryISOAlpha3,
        'numcode' => $faker->randomDigit,
        'phonecode' => $faker->randomDigit,
        'status' => 1
    ];
});
