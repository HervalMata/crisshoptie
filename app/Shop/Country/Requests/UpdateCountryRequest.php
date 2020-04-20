<?php


namespace App\shop\Countries\Requests;


use App\Shop\Base\BaseFormRequest;

class UpdateCountryRequest extends BaseFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'iso' => 'max:2',
            'iso3' => 'max:3',
            'numcode' => 'numeric',
            'phonecode' => 'numeric'
        ];
    }
}
