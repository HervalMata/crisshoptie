<?php


namespace App\Shop\Role\Requests;


use App\Shop\Base\BaseFormRequest;

class UpdateRoleRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => 'required',
            'roles' => 'array'
        ];
    }
}
