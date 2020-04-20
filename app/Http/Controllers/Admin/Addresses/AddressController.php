<?php


namespace App\Http\Controllers\Admin\Addresses;


use AddressTransformable;
use App\Http\Controllers\Controller;
use App\Shop\Address\Address;
use App\Shop\Address\Repositories\AddressRepository;
use App\Shop\Address\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Address\Requests\CreateAddressRequest;
use App\Shop\Address\Requests\UpdateAddressRequest;
use App\Shop\City\City;
use App\Shop\City\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\City\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\Country\Country;
use App\Shop\Country\Repositories\CountryRepository;
use App\Shop\Country\Repositories\Interfaces\CountryRepositoryInterface;
use App\Shop\Customer\Repositories\Interfaces\CustomerRepositoryInterface;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    use AddressTransformable;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepository;
    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * AddressController constructor.
     * @param AddressRepositoryInterface $addressRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param ProvinceRepositoryInterface $provinceRepository
     * @param CountryRepositoryInterface $countryRepository
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        CustomerRepositoryInterface $customerRepository,
        ProvinceRepositoryInterface $provinceRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->provinceRepository = $provinceRepository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $list = $this->addressRepository->listAddress('created_at', 'desc');

        if (request()->has('q')) {
            $list = $this->addressRepository->searchAddress(request()->input('q'));
        }
        $addresses = $list->map(function (Address $address) {
            return $this->transformAddress($address);
        })->all();

        return view('admin.addresses.list', ['addresses' => $this->addressRepository->paginateArrayResults($addresses)]);
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $countries = $this->countryRepository->listCountries();
        $country = $this->countryRepository->findCountryById(1);

        $customers = $this->customerRepository->listCustomers();
        return view('admin.addresses.create', [
            'customers' => $customers, 'countries' => $countries, 'provinces' => $country->provinces, 'cities' => City::all()
        ]);
    }

    /**
     * @param CreateAddressRequest $request
     * @return RedirectResponse
     */
    public function store(CreateAddressRequest $request)
    {
        $this->addressRepository->createAddress($request->except('_token', '_method'));
        $request->session()->flash('message', 'Endereço criado com sucesso.');
        return redirect()->route('admin.addresses.index');
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $address = $this->addressRepository->findAddressById($id);

        return view('admin.addresses.show', [
            'address' => $address,
        ]);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $countries = $this->countryRepository->listCountries();
        $country = $countries->filter(function ($country) {
            return $country == env('SHOP_COUNTRY_ID', '1');
        });
        $countryRepository = new CountryRepository(new Country);
        if (!empty($country)) {
            $countryRepository = new CountryRepository($country);
        }
        $address = $this->addressRepository->findAddressById($id);
        $addressRepository = new AddressRepository($address);
        $customer = $addressRepository->findCustomer();
        return view('admin.addresses.edit', [
            'address' => $address,
            'countries' => $countries,
            'countryId' => $address->country->id,
            'provinces' => $countryRepository->findProvinces(),
            'provinceId' => $address->province->id,
            'cities' => $this->cityRepository->listCities(),
            'cityId' => $address->city_id,
            'customer' => $this->customerRepository->listCustomers(),
            'customerId' => $customer->id
        ]);
    }

    /**
     * @param UpdateAddressRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateAddressRequest $request, $id)
    {
        $address = $this->addressRepository->findAddressById($id);

        $update = new AddressRepository($address);
        $update->updateAddress($request->except('_method', '_token'));

        $request->session()->flash('message', 'Endereço atualizado com sucesso.');
        return redirect()->route('admin.addresses.edit', $id);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        $address = $this->addressRepository->findAddressById($id);
        $delete = new addressRepository($address);

        $delete->deleteAddress();

        return redirect()->route('admin.addresses.index')->with('message', 'Endereço removido com sucesso.');
    }
}
