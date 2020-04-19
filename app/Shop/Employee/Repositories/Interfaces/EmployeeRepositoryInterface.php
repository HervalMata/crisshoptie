<?php


namespace App\Shop\Employee\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\Employee\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as Support;

interface EmployeeRepositoryInterface extends BaseRepositoryInterface
{
    public function listEmployees(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Support;

    public function createEmployee(array $params): Employee;

    public function updateEmployee(array $params): bool;

    public function findEmployeeByID(int $id): Employee;

    public function deleteEmployee(): bool;

    public function listRoles(): Collection;

    public function syncRoles(array $roleIds);

    public function hasRole(string $roleName): bool;

    public function isAuthUser(Employee $employee): bool;
}
