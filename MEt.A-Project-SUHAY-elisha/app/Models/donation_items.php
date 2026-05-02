<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class donation_items extends Model
{
    protected $table = 'donation_items';

    protected $fillable = [
        'donation_id',
        'inventory_item_id',
        'name',
        'quantity'
    ];

    public function donation()
    {
        return $this->belongsTo(donation_history::class, 'donation_id');
    }

    public function inventory()
    {
        return $this->belongsTo(inventory::class, 'inventory_item_id');
    }
}