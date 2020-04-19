<?php


namespace App\Http\Controllers\Admin\Customers;


use App\Http\Controllers\Controller;
use App\Shop\Customer\Customer;
use App\Shop\Customer\Repositories\CustomerRepository;
use App\Shop\Customer\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Shop\Customer\Repositories\UpdateCustomerInvalidArgumentException;
use App\Shop\Customer\Requests\CreateEmployeeRequest;
use App\Shop\Customer\Requests\UpdateEmployeeRequest;
use App\Shop\Customer\Transformations\CustomerTransformable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    use CustomerTransformable;

    /**
     * @var EmployeeRepositoryInterface
     */
    private $customerRepository;

    /**
     * CustomerController constructor.
     * @param EmployeeRepositoryInterface $customerRepository
     */
    public function __construct(EmployeeRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        $list = $this->customerRepository->listCustomers('created_at', 'desc');

        if (request()->has('q')) {
            $list = $this->customerRepository->searchCustomer(request()->input('q'));
        }
        $customers = $list->map(function (Customer $customer) {
            return $this->transformCustomer($customer);
        })->all();

        return view('admin.customers.list', ['customers' => $this->customerRepository->paginateArrayResults($customers)]);
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * @param CreateEmployeeRequest $request
     * @return RedirectResponse
     */
    public function store(CreateEmployeeRequest $request)
    {
        $this->customerRepository->createCustomer($request->except('_token', '_method'));
        return redirect()->route('admin.customers.index');
    }

    /**
     * @param int $id
     * @return Factory|View
     */
    public function show(int $id)
    {
        $customer = $this->customerRepository->findCustomer($id);

        return view('admin.customers.show', [
            'customer' => $customer,
        ]);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        return view('admin.customers.edit', ['customer' => $this->customerRepository->findCustomer($id)]);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws UpdateCustomerInvalidArgumentException
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
        $customer = $this->customerRepository->findCustomer($id);

        $update = new CustomerRepository($customer);
        $data = $request->except('_method', '_token', 'password');

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        $update->updateCustomer($data);
        $request->session()->flash('message', 'Atualização feita com sucesso.');
        return redirect()->route('admin.customers.edit', $id);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $customer = $this->customerRepository->findCustomer($id);
        $customerRepo = new CustomerRepository($customer);

        $customerRepo->deleteCustomer();
        return redirect()->route('admin.customers.index')->with('message', 'Removido com sucesso.');
    }
}
