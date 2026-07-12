<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'first_name', 'last_name', 'email', 'phone', 'role', 'verified',
        'address', 'street', 'city', 'state', 'country', 'zip_code', 'gender',
    ];
    public function devices()
    {
        return $this->hasMany(Device::class, 'customer_id');
    }
    public function addresses()
    {
        return $this->hasMany(DAddress::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'customer_order', 'customer_id', 'order_id')->withTimestamps();
    }
    public function defaultAddress(){
        return $this->hasOne(DAddress::class)->where('is_default', true);
    }
}
