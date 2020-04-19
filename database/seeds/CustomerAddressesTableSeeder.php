<?php

use App\Shop\Address\Address;
use App\Shop\Customer\Customer;
use Illuminate\Database\Seeder;

class CustomerAddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 3)->create()->each(function ($customer) {
            factory(Address::class, 3)->make()->each(function ($address) use ($customer) {
                $customer->address()->save($address);
            });
        });
    }
}
