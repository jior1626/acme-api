<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nit',
        'firstname',
        'lastname',
        'surnames',
        'address',
        'phone',
        'city',
        'type'
    ];

    public $with = [
        "OwnerCars"
    ];

    public function OwnerCars() {
        return $this->hasMany(Car::class, "owner_id", 'id');
    }

    public function DriverCars() {
        return $this->hasMany(Car::class, "driver_id", 'id');
    }
}
