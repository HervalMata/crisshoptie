<?php


namespace App\Shop\Role\Repositories\Interfaces;

use App\Repositories\BaseRepositoryInterface;
use App\Shop\Permission\Permission;
use App\Shop\Role\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function createRole(array $data): Role;

    public function listRoles(string $order = 'id', string $sort = 'desc'): Collection;

    public function findRoleById(int $id);

    public function updateRole(array $data): bool;

    public function deleteRoleById(): bool;

    public function attachToPermision(Permission $permission);

    public function attachToPermisions(...$permissions);

    public function syncPermissions(array $ids);

    public function listPermissions(): Collection;
}
