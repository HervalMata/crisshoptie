<?php


namespace App\Http\Controllers\Admin\Cities;


use App\Http\Controllers\Controller;
use App\Shop\City\Repositories\CityRepository;
use App\Shop\City\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\City\Requests\UpdateCityRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CityController extends Controller
{
    /**
     * @var CityRepositoryInterface
     */
    private $cityRepository;

    /**
     * CityController constructor.
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param $countryId
     * @param $provinceId
     * @param $city
     * @return Factory|View
     */
    public function edit($countryId, $provinceId, $city)
    {
        $city = $this->cityRepository->findCityById($city);
        return view('admin.cities.edit', [
            'countryId' => $countryId, 'provinceId' => $provinceId, 'city' => $city
        ]);
    }

    /**
     * @param UpdateCityRequest $request
     * @param $countryId
     * @param $provinceId
     * @param $city
     * @return RedirectResponse
     */
    public function update(UpdateCityRequest $request, $countryId, $provinceId, $city)
    {
        $city = $this->cityRepository->findCityById($city);
        $update = new CityRepository($city);
        $update->updateCity($request->only('name'));

        return redirect()->route('admin.countries.cities.edit', [$countryId, $provinceId, $city])->with('message', 'Cidade atualizada com sucesso.');
    }
}
