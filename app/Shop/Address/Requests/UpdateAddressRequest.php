<?php


namespace App\Shop\Address\Requests;


use App\Shop\Base\BaseFormRequest;

class UpdateAddressRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'alias' => 'required',
            'address_1' => 'required'
        ];
    }
}
