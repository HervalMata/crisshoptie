<?php

/** @var Factory $factory */

use App\Shop\Country\Country;
use App\Shop\State\State;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(State::class, function (Faker $faker) {

    $country = factory(Country::class)->create();

    return [
        'state' => $faker->city,
        'state_code' => $faker->word,
        'country_id' => $country->id
    ];
});
