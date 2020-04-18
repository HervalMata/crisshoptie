<?php


namespace App\Shop\Customer\Requests;


use App\Shop\Base\BaseFormRequest;

class CreateCustomerRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:customers'],
            'password' => ['required', 'min:8']
        ];
    }
}
