<?php

/** @var Factory $factory */

use App\Shop\Address\Address;
use App\Shop\Customer\Customer;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Address::class, function (Faker $faker) {
    $customer = factory(Customer::class)->create();
    return [
        'alias' => $faker->word,
        'address_1' => $faker->streetAddress,
        'address_2' => null,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'province_id' => 1,
        'country_id' => 1,
        'customer_id' => $customer,
        'status' => 1
    ];
});
