<?php


namespace App\Http\Controllers\Admin\Provinces;


use App\Http\Controllers\Controller;
use App\Shop\City\Exceptions\ProvinceNotFoundException;
use App\Shop\City\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\Province\Repositories\ProvinceRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProvinceController extends Controller
{
    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepository;

    /**
     * ProvinceController constructor.
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(ProvinceRepositoryInterface $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * @param int $countryId
     * @param int $provinceId
     * @return Factory|View
     */
    public function show(int $countryId, int $provinceId)
    {
        $province = $this->provinceRepository->findProvinceById($provinceId);
        $cities = $this->provinceRepository->listCities($provinceId);

        return view('admin.provinces.show', [
            'province' => $province, 'countryId' => $countryId, 'cities' => $this->provinceRepository->paginateArrayResults(collect($cities)->toArray())
        ]);
    }

    /**
     * @param int $countryId
     * @param int $provinceId
     * @return Factory|View
     */
    public function edit(int $countryId, int $provinceId)
    {
        return view('admin.provinces.edit', [
            'province' => $this->provinceRepository->findProvinceById($provinceId), 'countryId' => $countryId
        ]);
    }

    /**
     * @param Request $request
     * @param int $countryId
     * @param int $provinceId
     * @return RedirectResponse
     * @throws ProvinceNotFoundException
     */
    public function update(Request $request, int $countryId, int $provinceId)
    {
        $province = $this->provinceRepository->findProvinceById($provinceId);
        $update = new ProvinceRepository($province);
        $update->updateProvince($request->except('_method', '_token'));
        $request->session()->flash('message', 'Bairro atualizado com sucesso.');
        return redirect()->route('admin.countries.provinces.edit', [$countryId, $provinceId]);
    }
}
