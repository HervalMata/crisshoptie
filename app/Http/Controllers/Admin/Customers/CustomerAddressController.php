<?php


namespace App\Http\Controllers\Admin\Customers;


use App\Http\Controllers\Controller;
use App\Shop\Address\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\City\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\Country\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class CustomerAddressController extends Controller
{
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepository;
    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * CustomerAddressController constructor.
     * @param AddressRepositoryInterface $addressRepository
     * @param ProvinceRepositoryInterface $provinceRepository
     * @param CountryRepositoryInterface $countryRepository
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        ProvinceRepositoryInterface $provinceRepository,
        CountryRepositoryInterface $countryRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->provinceRepository = $provinceRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param int $addressId
     * @param int $customerId
     * @return Factory|View
     */
    public function show(int $addressId, int $customerId)
    {
        $address = $this->addressRepository->findAddressById($addressId);

        return view('admin.addresses.customers.show', [
            'address' => $address,
            'customerId' => $customerId
        ]);
    }

    /**
     * @param int $addressId
     * @param int $customerId
     * @return Factory|View
     */
    public function edit(int $addressId, int $customerId)
    {
        $this->countryRepository->findCountryById(env('COUNTRY_ID', 1));
        $province = $this->provinceRepository->findProvinceById(1);
        $countries = $this->countryRepository->listCountries();
        $address = $this->addressRepository->findAddressById($addressId);
        return view('admin.addresses.customers.edit', [
            'address' => $address,
            'countries' => $countries,
            'provinces' => $this->countryRepository->findProvinces(),
            'cities' => $this->provinceRepository->listCities($province->id),
            'customerId' => $customerId
        ]);
    }
}
