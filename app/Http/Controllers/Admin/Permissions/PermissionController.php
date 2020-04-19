<?php


namespace App\Http\Controllers\Admin\Permissions;


use App\Http\Controllers\Controller;
use App\Shop\Permission\Repositories\PermissionRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * PermissionController constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $list = $this->permissionRepository->listPermisions();
        $permissions = $this->permissionRepository->paginateArrayResults($list->all());
        return view('admin.permissions.list', compact('permissions'));
    }
}
