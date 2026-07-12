<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'address',
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'is_primary',
    ];
}
