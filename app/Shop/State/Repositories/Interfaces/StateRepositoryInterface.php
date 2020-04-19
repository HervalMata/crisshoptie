<?php


namespace App\Shop\State\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface StateRepositoryInterface extends BaseRepositoryInterface
{
    public function listCities(): Collection;
}
