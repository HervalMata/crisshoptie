<?php


namespace App\Shop\Address\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\Address\Address;
use App\Shop\City\City;
use App\Shop\Country\Country;
use App\Shop\Customer\Customer;
use App\Shop\Province\Province;
use Illuminate\Support\Collection;

interface AddressRepositoryInterface extends BaseRepositoryInterface
{
    public function createAddress(array $params): Address;

    public function attachToCustomer(Address $address, Customer $customer);

    public function updateAddress(array $update): bool;

    public function deleteAddress();

    public function listAddress($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;

    public function findAddressById(int $id): Address;

    public function findCustomer(): Customer;

    public function searchAddress(string $text): Collection;

    public function findCountry(): Country;

    public function findProvince(): Province;

    public function findCity(): City;
}
