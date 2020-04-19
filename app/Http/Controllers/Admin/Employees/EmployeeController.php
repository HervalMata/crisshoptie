<?php


namespace App\Http\Controllers\Admin\Employees;


use App\Http\Controllers\Controller;
use App\Shop\Employee\Repositories\EmployeeRepository;
use App\Shop\Employee\Requests\UpdateEmployeeRequest;
use App\Shop\Role\Repositories\RoleRepository;
use App\Shop\Role\Requests\CreateRoleRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     * RoleController constructor.
     * @param EmployeeRepository $employeeRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(EmployeeRepository $employeeRepository, RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $list = $this->employeeRepository->listEmployees('created_at', 'desc');
        return view('admin.employees.list', ['employees' => $this->employeeRepository->paginateArrayResults($list->all())]);
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $roles = $this->roleRepository->listRoles();
        return view('admin.employees.create', compact('roles'));
    }

    /**
     * @param CreateRoleRequest $request
     * @return RedirectResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $employee = $this->employeeRepository->createEmployee($request->all());
        if ($request->has('role')) {
            $employeeRepository = new EmployeeRepository($employee);
            $employeeRepository->syncRoles([$request->input('role')]);
        }
        return redirect()->route('admin.employees.index');
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $employee = $this->employeeRepository->findEmployeeById($id);

        return view('admin.employees.show', ['employee' => $employee]);
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function edit(int $id)
    {
        $employee = $this->employeeRepository->findEmployeeById($id);
        $roles = $this->roleRepository->listRoles('created_at', 'desc');
        $isCurrentUser = $this->employeeRepository->isAuthUser($employee);

        return view('admin.employees.edit', ['employee' => $employee, 'roles' => $roles, 'isCurrentUser' => $isCurrentUser, 'selectedIds' => $employee->roles()->pluck('role_id')->all()]);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeRepository->findEmployeeById($id);
        $isCurrentUser = $this->employeeRepository->isAuthUser($employee);

        $employeeRepo = new EmployeeRepository($employee);
        $employeeRepo->updateEmployee($request->except('_method', '_token', 'password'));

        if ($request->has('password') && !empty($request->input('password'))) {
            $employee->password = Hash::make($request->input('password'));
            $employee->save();
        }

        if ($request->has('roles') and !$isCurrentUser) {
            $employee->roles()->sync($request->input('roles'));
        } elseif (!$isCurrentUser) {
            $employee->roles()->detach();
        }

        return redirect()->route('admin.employees.edit', $id)->with('message', 'Funcionário atualizado com sucesso.');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $employee = $this->employeeRepository->findEmployeeById($id);
        $employeeRepo = new EmployeeRepository($employee);
        $employeeRepo->deleteEmployee();

        return redirect()->route('admin.employees.index')->with('message', 'Funcionário removido com sucesso.');
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function getProfile($id)
    {
        $employee = $this->employeeRepository->findEmployeeById($id);
        return view('admin.employees.profile', ['employee' => $employee]);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function updateProfile(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeRepository->findEmployeeById($id);
        $update = new EmployeeRepository($employee);
        $update->updateEmployee($request->except('_method', '_token', 'password'));
        if ($request->has('password') && $request->input('password') != '') {
            $update->updateEmployee($request->only('password'));
        }
        return redirect()->route('admin.employees.profile', $id)->with('message', 'Perfil do Funcionário atualizado com sucesso.');
    }
}
