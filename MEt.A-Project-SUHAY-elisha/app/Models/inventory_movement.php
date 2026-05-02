<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inventory_movement extends Model
{
    protected $table = 'inventory_movement';

    protected $fillable = [
        'account_at',
        'ngo_id',
        'date_updated',
        'remarks',
        'movement_type'
    ];

    protected $casts = [
    'movement_type' => 'integer',
    'date_updated' => 'date'
    ];

    
    public function items()
    {
        return $this->hasMany(inventory_movement_items::class, 'movement_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_at');
    }
}