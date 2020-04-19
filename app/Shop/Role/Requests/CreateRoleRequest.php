<?php


namespace App\Shop\Role\Requests;


use App\Shop\Base\BaseFormRequest;

class CreateRoleRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:roles'
        ];
    }
}
