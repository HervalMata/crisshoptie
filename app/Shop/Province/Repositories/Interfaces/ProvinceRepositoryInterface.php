<?php


namespace App\Shop\City\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\Country\Country;
use App\Shop\Province\Province;
use Illuminate\Support\Collection;

interface ProvinceRepositoryInterface extends BaseRepositoryInterface
{
    public function listProvinces(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    public function findProvinceById(int $id): Province;

    public function updateProvince(array $params): bool;

    public function listCities(int $provinceId);

    public function findCountry(): Country;
}
