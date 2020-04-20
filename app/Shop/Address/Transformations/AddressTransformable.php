<?php


use App\Shop\Address\Address;
use App\Shop\City\Exceptions\ProvinceNotFoundException;
use App\Shop\Country\Country;
use App\Shop\Country\Exceptions\CountryNotFoundException;
use App\Shop\Country\Repositories\CountryRepository;
use App\Shop\Customer\Customer;
use App\Shop\Customer\Exceptions\CustomerNotFoundException;
use App\Shop\Customer\Repositories\CustomerRepository;
use App\Shop\Province\Province;
use App\Shop\Province\Repositories\ProvinceRepository;

trait AddressTransformable
{
    /**
     * @param Address $address
     * @return Address
     * @throws ProvinceNotFoundException
     * @throws CountryNotFoundException
     * @throws CustomerNotFoundException
     */
    public function transformAddress(Address $address)
    {
        $obj = new Address;
        $obj->id = $address->id;
        $obj->alias = $address->alias;
        $obj->address_1 = $address->address_1;
        $obj->address_2 = $address->address_2;
        $obj->zip = $address->zip;
        $obj->city = $address->city;

        if (isset($address->province_id)) {
            $provinceRepository = new ProvinceRepository(new Province);
            $province = $provinceRepository->findProvinceById($address->province_id);
            $obj->province = $province->name;
        }

        $countryRepository = new CountryRepository(new Country);
        $country = $countryRepository->findCountryById($address->country_id);
        $obj->country = $country->name;

        $customerRepository = new CustomerRepository(new Customer);
        $customer = $customerRepository->findCustomer($address->customer_id);
        $obj->customer = $customer->name;
        $obj->status = $address->status;

        return $obj;
    }
}
