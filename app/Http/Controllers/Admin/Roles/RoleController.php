<?php


namespace App\Http\Controllers\Admin\Roles;


use App\Http\Controllers\Controller;
use App\Shop\Permission\Repositories\PermissionRepository;
use App\Shop\Role\Exceptions\CreateRoleErrorException;
use App\Shop\Role\Exceptions\RoleNotFoundErrorException;
use App\Shop\Role\Exceptions\UpdateRoleErrorException;
use App\Shop\Role\Repositories\RoleRepository;
use App\Shop\Role\Requests\CreateRoleRequest;
use App\Shop\Role\Requests\UpdateRoleRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * RoleController constructor.
     * @param RoleRepository $roleRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $list = $this->roleRepository->listRoles('name', 'asc')->all();
        $roles = $this->roleRepository->paginateArrayResults($list);
        return view('admin.roles.list', compact('roles'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * @param CreateRoleRequest $request
     * @return RedirectResponse
     * @throws CreateRoleErrorException
     */
    public function store(CreateRoleRequest $request)
    {
        $this->roleRepository->createRole($request->except('_method', '_token'));
        return redirect()->route('admin.roles.index')->with('message', 'Função criada com sucesso.');
    }

    /**
     * @param $id
     * @return Factory|View
     * @throws RoleNotFoundErrorException
     */
    public function edit($id)
    {
        $role = $this->roleRepository->findRoleById($id);
        $roleRepo = new RoleRepository($role);
        $attachedPernissionsArrayIds = $roleRepo->listPermissions()->pluck('id')->all();
        $permissions = $this->permissionRepository->listPermisions(['*'], 'name', 'asc');

        return view('admin.roles.edit', compact('role', 'permissions', 'attachedPernissionsArrayIds'));
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws RoleNotFoundErrorException
     * @throws UpdateRoleErrorException
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->roleRepository->findRoleById($id);
        if ($request->has('permissions')) {
            $roleRepo = new RoleRepository($role);
            $roleRepo->syncPermissions($request->input('permissions'));
        }

        $this->roleRepository->updateRole($request->except('_method', '_token'), $id);

        return redirect()->route('admin.roles.edit', $id)->with('message', 'Função atualizada com sucesso.');
    }
}
