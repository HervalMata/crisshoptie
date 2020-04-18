<?php


namespace App\Shop\Customer\Requests;


use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('customers')->ignore($this->segment(3))]
        ];
    }
}
