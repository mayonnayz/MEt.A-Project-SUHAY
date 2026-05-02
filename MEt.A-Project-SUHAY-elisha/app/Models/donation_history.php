<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class donation_history extends Model
{
    protected $table = 'donation_history';

    protected $fillable = [
        'account_id',
        'type',
        'payment_type',
        'reference_number',
        'ngo_id',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(donation_items::class, 'donation_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function ngo()
    {
        return $this->belongsTo(NgoProfile::class, 'ngo_id');
    }
}