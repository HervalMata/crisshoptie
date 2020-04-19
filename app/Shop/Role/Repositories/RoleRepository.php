<?php


namespace App\Shop\Role\Repositories;


use App\Repositories\BaseRepository;
use App\Shop\Permission\Permission;
use App\Shop\Role\Exceptions\CreateRoleErrorException;
use App\Shop\Role\Exceptions\DeleteRoleErrorException;
use App\Shop\Role\Exceptions\RoleNotFoundErrorException;
use App\Shop\Role\Exceptions\UpdateRoleErrorException;
use App\Shop\Role\Repositories\Interfaces\RoleRepositoryInterface;
use App\Shop\Role\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /** @var Role */
    protected $model;

    /**
     * RoleRepository constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        parent::__construct($role);
        $this->model = $role;
    }

    /**
     * @param string $order
     * @param string $sort
     * @return Collection
     */
    public function listRoles(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->all(['*'], $order, $sort);
    }

    /**
     * @param array $data
     * @return Role
     * @throws CreateRoleErrorException
     */
    public function createRole(array $data): Role
    {
        try {
            $role = new Role($data);
            $role->save();
            return $role;
        } catch (QueryException $e) {
            throw new CreateRoleErrorException($e);
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws RoleNotFoundErrorException
     */
    public function findRoleById(int $id)
    {
        try {
            return $this->findOneOrFail($id);
        } catch (QueryException $e) {
            throw new RoleNotFoundErrorException($e);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws UpdateRoleErrorException
     */
    public function updateRole(array $data): bool
    {
        try {
            return $this->update($data);
        } catch (QueryException $e) {
            throw new UpdateRoleErrorException($e);
        }
    }

    /**
     * @return bool
     * @throws DeleteRoleErrorException
     */
    public function deleteRoleById(): bool
    {
        try {
            return $this->delete();
        } catch (QueryException $e) {
            throw new DeleteRoleErrorException($e);
        }
    }

    /**
     * @param Permission $permission
     */
    public function attachToPermision(Permission $permission)
    {
        $this->model->attachPermission($permission);
    }

    /**
     * @param mixed ...$permissions
     */
    public function attachToPermisions(...$permissions)
    {
        $this->model->attachPermissions($permissions);
    }

    /**
     * @param array $ids
     */
    public function syncPermissions(array $ids)
    {
        $this->model->syncPermissions($ids);
    }

    /**
     * @return Collection
     */
    public function listPermissions(): Collection
    {
        $this->model->permissions()->get();
    }
}
