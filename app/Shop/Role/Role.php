<?php

namespace App\Shop\Role;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $fillable = [
        'name',
        'dysplay_name',
        'description'
    ];
}
