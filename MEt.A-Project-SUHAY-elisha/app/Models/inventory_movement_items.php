<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inventory_movement_items extends Model
{
    protected $table = 'inventory_movement_items';

    protected $fillable = [
        'movement_id',
        'inventory_item_id',
        'donated_item_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];
    public function movement()
    {
        return $this->belongsTo(inventory_movement::class, 'movement_id');
    }

    public function inventory()
    {
        return $this->belongsTo(inventory::class, 'inventory_item_id');
    }

    public function donationItem()
    {
        return $this->belongsTo(donation_items::class, 'donated_item_id');
    }

    protected static function booted()
    {
        static::created(function ($item) {

            $inventory = inventory::find($item->inventory_item_id);

            if (!$inventory) return;

            // movement_type: 0 = IN (donation), 1 = OUT (usage)
            if ($item->movement->movement_type == 0) {
                $inventory->current_quantity += $item->quantity;
            } else {
                $inventory->current_quantity -= $item->quantity;
            }

            $inventory->save();
        });
    }
}