<?php


namespace App\Shop\Province\Repositories;


use App\Repositories\BaseRepository;
use App\Shop\City\Exceptions\ProvinceNotFoundException;
use App\Shop\City\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Shop\Country\Country;
use App\Shop\Province\Province;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    /**
     * ProvinceRepository constructor.
     * @param Province $province
     */
    public function __construct(Province $province)
    {
        parent::__construct($province);
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listProvinces(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param array $params
     * @return bool
     * @throws ProvinceNotFoundException
     */
    public function updateProvince(array $params): bool
    {
        try {
            return $this->model->update($params);
        } catch (QueryException $e) {
            throw new ProvinceNotFoundException($e->getMessage());
        }
    }

    /**
     * @param int $provinceId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listCities(int $provinceId)
    {
        return $this->findProvinceById($provinceId)->cities()->get();
    }

    /**
     * @param int $id
     * @return Province
     * @throws ProvinceNotFoundException
     */
    public function findProvinceById(int $id): Province
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ProvinceNotFoundException($e->getMessage());
        }
    }

    /**
     * @return Country
     */
    public function findCountry(): Country
    {
        return $this->model->country;
    }
}
