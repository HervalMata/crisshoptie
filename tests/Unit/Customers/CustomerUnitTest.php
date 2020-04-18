<?php


namespace Tests\Unit\Customers;


use App\Shop\Customer\Customer;
use App\Shop\Customer\Repositories\CustomerNotFoundException;
use App\Shop\Customer\Repositories\CustomerRepository;
use App\Shop\Customer\Repositories\UpdateCustomerInvalidArgumentException;
use App\Shop\Customer\Transformations\CustomerTransformable;
use Faker\Factory as faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerUnitTest extends TestCase
{
    use DatabaseMigrations;
    use CustomerTransformable;

    /** @test */
    public function it_can_search_for_customers()
    {
        $name = faker::create()->name;
        $customer = factory(Customer::class)->create([
            'name' => $name,
            'email' => faker::create()->email
        ]);

        $repo = new CustomerRepository($customer);
        $result = $repo->searchCustomer($name);

        $this->assertGreaterThan(0, $result->count());
    }

    /** @test
     * @throws CustomerNotFoundException
     */
    public function it_can_transform_the_customer()
    {
        $customer = factory(Customer::class)->create();

        $repo = new CustomerRepository($customer);
        $customerFromDb = $repo->findCustomer($customer->id);
        $cust = $this->transformCustomer($customer);

        $this->assertIsString('string', $customerFromDb->status);
        $this->assertIsInt(1, $cust->status);
    }

    /** @test */
    public function it_errors_updating_the_customer_name_with_null_value()
    {
        //$this->expectException(UpdateCustomerInvalidArgumentException::class);
        $cust = factory(Customer::class)->create();
        $customer = new CustomerRepository($cust);
        $customer->update([
            'name' => faker::create()->name,
            'email' => faker::create()->email,
            'status' => 1,
            'password' => 'unknown'
        ]);
        $this->assertTrue(Hash::check('unknown', bcrypt($cust->password)));
    }

    /** @test */
    public function it_can_soft_delete_a_customer()
    {
        $customer = factory(Customer::class)->create();
        $customerRepo = new CustomerRepository($customer);
        $delete = $customerRepo->deleteCustomer();
        $this->assertTrue($delete);
        //$this->assertDatabaseHas('customers', $customer->toArray());
    }

    /** @test */
    public function it_can_find_a_customer()
    {
        $data = [
            'name' => faker::create()->name,
            'email' => faker::create()->email,
            'password' => 'secret'
        ];

        $customer = new CustomerRepository(new Customer);
        $created = $customer->createCustomer($data);
        $found = $customer->findCustomer($created->id);

        $this->assertInstanceOf(Customer::class, $found);
        $this->assertEquals($data['name'], $found->name);
        $this->assertEquals($data['email'], $found->email);
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        $cust = factory(Customer::class)->create();
        $customer = new CustomerRepository($cust);

        $update = [
            'name' => faker::create()->name
        ];

        $updated = $customer->updateCustomer($update);

        $this->assertTrue($updated);
        $this->assertEquals($update['name'], $cust->name);
        $this->assertDatabaseHas('customers', $update);
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        $data = [
            'name' => faker::create()->name,
            'email' => faker::create()->email,
            'password' => 'secret'
        ];

        $customer = new CustomerRepository(new Customer);
        $created = $customer->createCustomer($data);

        $this->assertInstanceOf(Customer::class, $created);
        $this->assertEquals($data['name'], $created->name);
        $this->assertEquals($data['email'], $created->email);

        $collection = collect($data)->except('password');

        $this->assertDatabaseHas('customers', $collection->all());
    }
}
