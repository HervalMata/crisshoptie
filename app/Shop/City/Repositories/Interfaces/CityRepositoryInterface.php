<?php


namespace App\Shop\City\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\City\City;

interface CityRepositoryInterface extends BaseRepositoryInterface
{
    public function listCities();

    public function findCityById(int $id): City;

    public function updateCity(array $params): bool;

    public function findCityByName(string $name): City;
}
