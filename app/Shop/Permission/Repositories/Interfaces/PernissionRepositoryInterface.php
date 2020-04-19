<?php


namespace App\Shop\Permission\Repositories\Interfaces;


use App\Repositories\BaseRepositoryInterface;
use App\Shop\Permission\Permission;
use Illuminate\Support\Collection;

interface PernissionRepositoryInterface extends BaseRepositoryInterface
{
    public function createPermission(array $data): Permission;

    public function findPermissionById(int $id): Permission;

    public function updatePermission(array $data): bool;

    public function deletePermission(): bool;

    public function listPermisions($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc'): Collection;
}
