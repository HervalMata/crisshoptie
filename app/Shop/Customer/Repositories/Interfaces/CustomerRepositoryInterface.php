<?php


namespace App\Shop\Customer\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\Customer\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as Support;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function listCustomers(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Support;

    public function createCustomer(array $params): Customer;

    public function updateCustomer(array $params): bool;

    public function findCustomer(int $id): Customer;

    public function deleteCustomer(): bool;

    public function searchCustomer(string $text): Collection;
}
