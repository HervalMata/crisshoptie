<?php


namespace App\Shop\Address;


use App\Shop\City\City;
use App\Shop\Country\Country;
use App\Shop\Customer\Customer;
use App\Shop\Province\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Address extends Model
{
    use SoftDeletes;
    use SearchableTrait;

    public $fillable = [
        'alias',
        'address_1',
        'address_2',
        'zip',
        'city',
        'state_code',
        'province_id',
        'country_id',
        'customer_id',
        'status',
        'phone',
        'mobile'
    ];

    protected $hidden = [];

    protected $dates = ['deleted_at'];

    protected $searchable = [
        'columns' => [
            'alias' => 5,
            'address_1' => 10,
            'address_2' => 5,
            'zip' => 5,
            'city' => 10,
            'state_code' => 10,
            'phone' => 5,
            'mobile' => 5
        ]
    ];

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @param $term
     * @return mixed
     */
    public function searchAddress($term)
    {
        return self::search($term);
    }
}
