<?php

use App\Shop\Employee\Employee;
use App\Shop\Permission\Permission;
use App\Shop\Role\Repositories\RoleRepository;
use App\Shop\Role\Role;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createProductPerm = factory(Permission::class)->create([
            'name' => 'create-product',
            'display_name' => 'Criar produto'
        ]);

        $viewProductPerm = factory(Permission::class)->create([
            'name' => 'view-product',
            'display_name' => 'Visualizar produto'
        ]);

        $updateProductPerm = factory(Permission::class)->create([
            'name' => 'update-product',
            'display_name' => 'Atualizar produto'
        ]);

        $deleteProductPerm = factory(Permission::class)->create([
            'name' => 'delete-product',
            'display_name' => 'Remover produto'
        ]);

        $updateOrderPerm = factory(Permission::class)->create([
            'name' => 'update-order',
            'display_name' => 'Alterar ordem'
        ]);

        $employee = factory(Employee::class)->create([
            'name' => 'Herval',
            'email' => 'admin@admin.com'
        ]);

        $super = factory(Role::class)->create([
            'name' => 'superadmin',
            'display_name' => 'Super Admin'
        ]);

        $roleSuperRepo = new RoleRepository($super);
        $roleSuperRepo->attachToPermision($createProductPerm);
        $roleSuperRepo->attachToPermision($viewProductPerm);
        $roleSuperRepo->attachToPermision($updateProductPerm);
        $roleSuperRepo->attachToPermision($deleteProductPerm);
        $roleSuperRepo->attachToPermision($updateOrderPerm);

        $employee->roles()->save($super);

        $employee = factory(Employee::class)->create([
            'name' => 'Usuário',
            'email' => 'user@admin.com'
        ]);

        $user = factory(Role::class)->create([
            'name' => 'superadmin',
            'display_name' => 'Super Admin'
        ]);

        $roleUserRepo = new RoleRepository($user);
        $roleUserRepo->attachToPermision($createProductPerm);
        $roleUserRepo->attachToPermision($viewProductPerm);
        $roleUserRepo->attachToPermision($updateProductPerm);

        $employee->roles()->save($user);
    }
}
