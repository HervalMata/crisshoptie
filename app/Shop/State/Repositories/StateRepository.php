<?php


namespace App\Shop\State\Repositories;


use App\Repositories\BaseRepository;
use App\Shop\City\City;
use App\Shop\City\Repositories\CityRepository;
use App\Shop\State\Repositories\Interfaces\StateRepositoryInterface;
use App\Shop\State\State;
use Illuminate\Support\Collection;

class StateRepository extends BaseRepository implements StateRepositoryInterface
{
    /**
     * StateRepository constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        parent::__construct($state);
        $this->model = $state;
    }

    public function listCities(): Collection
    {
        $cityRepository = new CityRepository(new City);
        return $cityRepository->listCitiesByStateCode($this->model->state_code);
    }
}
