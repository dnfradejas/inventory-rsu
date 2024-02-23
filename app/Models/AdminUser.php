<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use HasFactory;


    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    protected $fillable = [
        'role_id',
        'fullname',
        'username',
        'password',
        'status',
    ];
}
