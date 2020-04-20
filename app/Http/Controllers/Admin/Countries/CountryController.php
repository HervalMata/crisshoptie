<?php


namespace App\Http\Controllers\Admin\Countries;


use App\Http\Controllers\Controller;
use App\shop\Countries\Requests\UpdateCountryRequest;
use App\Shop\Country\Repositories\CountryRepository;
use App\Shop\Country\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CountryController extends Controller
{
    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * CountryController constructor.
     * @param CountryRepositoryInterface $countryRepository
     */
    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $list = $this->countryRepository->listCountries('created_at', 'desc');

        return view('admin.countries.list', ['countries' => $this->countryRepository->paginateArrayResults($list->all(), 10)]);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $country = $this->countryRepository->findCountryById($id);
        $countryRepository = new CountryRepository($country);
        $provinces = $countryRepository->findProvinces();

        return view('admin.countries.show', [
            'countries' => $country, 'provinces' => $this->countryRepository->paginateArrayResults($provinces->toArray())
        ]);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function edit(int $id)
    {
        return view('admin.countries.edit', ['country' => $this->countryRepository->findCountryById($id)]);
    }

    /**
     * @param UpdateCountryRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $country = $this->countryRepository->findCountryById($id);
        $update = new CountryRepository($country);
        $update->updateCountry($request->except('_method', '_token'));

        $request->session()->flash('message', 'País atualizado com sucesso.');
        return redirect()->route('admin.countries.edit', $id);
    }
}
