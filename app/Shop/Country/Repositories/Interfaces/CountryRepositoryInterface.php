<?php


namespace App\Shop\Country\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\Country\Country;
use Illuminate\Support\Collection;

interface CountryRepositoryInterface extends BaseRepositoryInterface
{
    public function updateCountry(array $params): Country;

    public function listCountries($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;

    public function createCountry(array $params): Country;

    public function findCountryById(int $id): Country;

    public function findProvinces();

    public function listStates(): Collection;
}
