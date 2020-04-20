<?php


namespace App\Shop\Address\Repositories;


use AddressTransformable;
use App\Repositories\BaseRepository;
use App\Shop\Address\Address;
use App\Shop\Address\Exceptions\AddressNotFoundException;
use App\Shop\Address\Exceptions\CreateAddressErrorException;
use App\Shop\Address\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\City\City;
use App\Shop\Country\Country;
use App\Shop\Customer\Customer;
use App\Shop\Province\Province;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class AddressRepository extends BaseRepository implements AddressRepositoryInterface
{
    use AddressTransformable;

    /**
     * AddressRepository constructor.
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        parent::__construct($address);
        $this->model = $address;
    }

    /**
     * @param array $data
     * @return Address
     * @throws CreateAddressErrorException
     */
    public function createAddress(array $data): Address
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new CreateAddressErrorException('Endereço com erro.');
        }
    }

    /**
     * @param Address $address
     * @param Customer $customer
     */
    public function attachToCustomer(Address $address, Customer $customer)
    {
        $customer->addresses()->save($address);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateAddress(array $data): bool
    {
        return $this->update($data);
    }

    /**
     * @return bool|null
     * @throws Exception
     */
    public function deleteAddress()
    {
        $this->model->customer()->dissociate();
        return $this->model->delete();
    }

    /**
     * @param array $columns
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listAddress($columns = array('*'), string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return Address
     * @throws AddressNotFoundException
     */
    public function findAddressById(int $id): Address
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new AddressNotFoundException('Endereço não encontrado.');
        }
    }

    /**
     * @param int $id
     * @param Customer $customer
     * @return Customer
     * @throws AddressNotFoundException
     */
    public function findCustomerAddressById(int $id, Customer $customer): Customer
    {
        try {
            return $customer
                ->addresses()
                ->whereId($id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new AddressNotFoundException('Endereço não encontrado.');
        }
    }

    /**
     * @return Customer
     */
    public function findCustomer(): Customer
    {
        return $this->model->customer;
    }

    /**
     * @param string $text
     * @return Collection
     */
    public function searchAddress(string $text): Collection
    {
        if (is_null($text)) {
            return $this->all(['*'], 'address_1', 'asc');
        }
        return $this->model->searchAddress($text)->get();
    }

    /**
     * @return Country
     */
    public function findCountry(): Country
    {
        return $this->model->country;
    }

    /**
     * @return Province
     */
    public function findProvince(): Province
    {
        return $this->model->province;
    }

    /**
     * @return City
     */
    public function findCity(): City
    {
        return $this->model->city;
    }
}
