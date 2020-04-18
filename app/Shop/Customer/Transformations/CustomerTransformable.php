<?php


namespace App\Shop\Customer\Transformations;


use App\Shop\Customer\Customer;

trait CustomerTransformable
{
    /**
     * @param Customer $customer
     * @return Customer
     */
    protected function transformCustomer(Customer $customer)
    {
        $prop = new Customer;
        $prop->id = (int)$customer->id;
        $prop->name = $customer->name;
        $prop->email = $customer->email;
        $prop->status = (int)$customer->status;

        return $prop;
    }
}
