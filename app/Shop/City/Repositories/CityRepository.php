<?php


namespace App\Shop\City\Repositories;


use App\Repositories\BaseRepository;
use App\Shop\City\City;
use App\Shop\City\Exceptions\CityNotFoundException;
use App\Shop\City\Repositories\Interfaces\CityRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    /**
     * CityRepository constructor.
     * @param City $city
     */
    public function __construct(City $city)
    {
        parent::__construct($city);
        $this->model = $city;
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function listCities($columns = ['*'], string $orderBy = 'name', string $sortBy = 'asc')
    {
        return $this->all($columns, $orderBy, $sortBy);
    }

    /**
     * @param int $id
     * @return City
     * @throws CityNotFoundException
     */
    public function findCityById(int $id): City
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CityNotFoundException('Cidade não encontrada.');
        }
    }

    /**
     * @param array $params
     * @return bool
     */
    public function updateCity(array $params): bool
    {
        $this->model->update($params);
        return $this->model->save();
    }

    /**
     * @param string $state_code
     * @return Collection
     */
    public function listCitiesByStateCode(string $state_code): Collection
    {
        return $this->model->where(compact('status_code'))->get();
    }

    /**
     * @param string $name
     * @return City
     * @throws CityNotFoundException
     */
    public function findCityByName(string $name): City
    {
        try {
            return $this->model->where(compact('name'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new CityNotFoundException('Cidade não encontrada.');
        }
    }
}
