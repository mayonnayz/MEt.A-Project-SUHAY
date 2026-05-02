<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class account extends Authenticatable
{
    protected $table = 'accounts';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_picture',
        'roles',
        'status',
        'ngo_id',
        'address',
        'birth_date',
        'contact_number'
    ];

    protected $hidden = [
        'password'
    ];

    /*
    |-----------------------------------------
    | RELATIONSHIPS
    |-----------------------------------------
    */

    // A user belongs to an NGO
    public function ngo()
    {
        return $this->belongsTo(ngo_profile::class, 'ngo_id');
    }

    // A user can have many donations
    public function donations()
    {
        return $this->hasMany(donation_history::class, 'account_id');
    }

    // A user can create many inventory movements
    public function inventoryMovements()
    {
        return $this->hasMany(inventory_movement::class, 'account_at');
    }

    /*
    |-----------------------------------------
    | ACCESSORS (optional but useful)
    |-----------------------------------------
    */

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}