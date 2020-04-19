<?php

/** @var Factory $factory */

use App\Shop\City\City;
use App\Shop\Province\Province;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(City::class, function (Faker $faker) {
    $province = factory(Province::class)->create();
    return [
        'name' => $faker->city,
        'province_id' => $province->id
    ];
});
