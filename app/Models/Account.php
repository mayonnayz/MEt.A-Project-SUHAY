<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Account extends Model
{
    protected $table = 'accounts';
 
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'roles',
        'status',
        'ngo_id',
        'birth_date',
        'contact_number',
    ];
 
    protected $hidden = [
        'password',
    ];
 
    protected $casts = [
        'status'     => 'integer',
        'birth_date' => 'date',
    ];
}