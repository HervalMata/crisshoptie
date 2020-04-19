<?php


namespace App\Shop\City;


use App\Shop\Province\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'province_id'
    ];
    protected $hidden = [];

    /**
     * @return BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
