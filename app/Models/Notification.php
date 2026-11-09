<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'reference_id',
        'is_read',
        'related_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}