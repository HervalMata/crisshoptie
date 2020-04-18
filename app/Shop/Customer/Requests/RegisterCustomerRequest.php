<?php


namespace App\Shop\Customer\Requests;


use App\Shop\Base\BaseFormRequest;

class RegisterCustomerRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6|confirmed'
        ];
    }
}
