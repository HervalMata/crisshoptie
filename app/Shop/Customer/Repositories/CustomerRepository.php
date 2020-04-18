<?php


namespace App\Shop\Customer\Repositories;


use App\Repositories\BaseRepository;
use App\Shop\Customer\Customer;
use App\Shop\Customer\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection as Support;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{

    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
        $this->model = $customer;
    }

    public function listCustomers(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Support
    {
        return $this->all($columns, $order, $sort);
    }

    public function createCustomer(array $params): Customer
    {
        try {
            $data = collect($params)->except('password')->all();
            $customer = new Customer($data);
            if (isset($params['password'])) {
                $customer->password = bcrypt($params['password']);
            }

            $customer->save();
            return $customer;
        } catch (QueryException $e) {
            throw new CreateCustomerInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }

    public function updateCustomer(array $params): bool
    {
        try {
            return $this->model->update($params);
        } catch (QueryException $e) {
            throw new UpdateCustomerInvalidArgumentException($e);
        }
    }

    public function findCustomer(int $id): Customer
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CustomerNotFoundException($e);
        }
    }

    public function deleteCustomer(): bool
    {
        return $this->delete();
    }

    /**
     * @param string $text
     * @return \League\Fractal\Resource\Collection
     */
    public function searchCustomer(string $text = null): Collection
    {
        if (is_null($text)) {
            return $this->all();
        }
        return $this->model->searchCustomer($text)->get();
    }
}
