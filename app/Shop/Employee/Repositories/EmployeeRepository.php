<?php

namespace App\Shop\Employee\Repositories;

use App\Repositories\BaseRepository;
use App\Shop\Employee\Employee;
use App\Shop\Employee\Exceptions\EmployeeNotFoundException;
use App\Shop\Employee\Repositories\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection as Support;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{

    /**
     * EmployeeRepository constructor.
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        parent::__construct($employee);
        $this->model = $employee;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Support
     */
    public function listEmployees(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Support
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param array $data
     * @return Employee
     */
    public function createEmployee(array $data): Employee
    {

        $data['password'] = Hash::make($data['password']);

        return $this->create($data);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function updateEmployee(array $params): bool
    {
        if (isset($params['password'])) {
            $params['passord'] = Hash::make($params['password']);
        }

        return $this->update($params);
    }

    /**
     * @param int $id
     * @return Employee
     */
    public function findEmployeeById(int $id): Employee
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new EmployeeNotFoundException($e);
        }
    }

    /**
     * @return bool
     */
    public function deleteEmployee(): bool
    {
        return $this->delete();
    }

    /**
     * @param array $roleIds
     */
    public function syncRoles(array $roleIds)
    {
        $this->model->roles()->sync($roleIds);
    }

    /**
     * @return Collection
     */
    public function listRoles(): Collection
    {
        return $this->model->roles()->get();
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->model->hasRole($roleName);
    }

    /**
     * @param Employee $employee
     * @return bool
     */
    public function isAuthUser(Employee $employee): bool
    {
        $isAuthUser = false;
        if (Auth::guard('employee')->user()->id == $employee->id) {
            $isAuthUser = true;
        }
        return $isAuthUser;
    }

}
