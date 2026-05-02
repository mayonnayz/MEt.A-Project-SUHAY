<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'ngo_id',
        'name',
        'current_quantity',
        'status',
        'unit',
        'category',
        'minimum_threshold'
    ];

    protected $casts = [
        'current_quantity' => 'integer',
        'minimum_threshold' => 'integer',
        'status' => 'integer'
    ];

    public function ngo()
    {
        return $this->belongsTo(ngo_profile::class, 'ngo_id');
    }

    public function donationItems()
    {
        return $this->hasMany(donation_items::class, 'inventory_item_id');
    }

    // ✅ ADD THIS (important for movement connection)
    public function movements()
    {
        return $this->hasMany(inventory_movement_items::class, 'inventory_item_id');
    }

    // ✅ OPTIONAL: clean stock status logic (VERY useful for UI)
    public function getStockStatusAttribute()
    {
        if ($this->current_quantity == 0) {
            return 'No Stock';
        }

        if ($this->current_quantity <= $this->minimum_threshold) {
            return 'Low Stock';
        }

        return 'In Stock';
    }
}