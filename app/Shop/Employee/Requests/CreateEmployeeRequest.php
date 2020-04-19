<?php


namespace App\Shop\Employee\Requests;


use App\Shop\Base\BaseFormRequest;

class CreateEmployeeRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:employees'],
            'password' => ['required', 'min:8'],
            'role' => ['required']
        ];
    }
}
