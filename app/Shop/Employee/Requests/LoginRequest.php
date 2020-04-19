<?php


namespace App\Shop\Employee\Requests;


use App\Shop\Base\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }
}
