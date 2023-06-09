<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration',
        'color',
        'brand',
        'type',
        'owner_id',
        'driver_id'
    ];

    protected $with = [
        "Owner",
        "Driver"
    ];

    public function Owner(){
        return $this->belongsTo(User::class);
    }

    public function Driver(){
        return $this->hasMany(Message::class);
    }
}
