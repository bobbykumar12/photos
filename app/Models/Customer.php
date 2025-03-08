<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Authenticatable 
{
    use HasFactory;

    // Define the table name (if different from default)
    protected $table = 'customers';

    // Define the fillable fields
    protected $fillable = [
        'google_id',
        'google_access_token',
        'google_user_name',
        'email',
        'google_refresh_token'
    ];
}


