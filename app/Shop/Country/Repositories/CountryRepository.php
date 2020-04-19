<?php


namespace App\Shop\Country\Repositories;


use App\Repositories\BaseRepository;
use App\Shop\Country\Country;
use App\Shop\Country\Exceptions\CountryInvalidArgumentException;
use App\Shop\Country\Exceptions\CountryNotFoundException;
use App\Shop\Country\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    /**
     * CountryRepository constructor.
     * @param Country $country
     */
    public function __construct(Country $country)
    {
        parent::__construct($country);
        $this->model = $country;
    }

    /**
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listCountries(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->model->where('status', 1)->get();
    }

    /**
     * @param array $params
     * @return Country
     */
    public function createCountry(array $params): Country
    {
        return $this->create($params);
    }

    /**
     * @param int $id
     * @return Country
     * @throws CountryNotFoundException
     */
    public function findCountryById(int $id): Country
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CountryNotFoundException('País não encontrado.');
        }
    }

    /**
     * @return mixed
     */
    public function findProvinces()
    {
        return $this->model->provinces;
    }

    /**
     * @param array $params
     * @return Country
     * @throws CountryInvalidArgumentException
     */
    public function updateCountry(array $params): Country
    {
        try {
            $this->model->update($params);
        } catch (QueryException $e) {
            throw new CountryInvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @return Collection
     */
    public function listStates(): Collection
    {
        return $this->model->states()->get();
    }
}
