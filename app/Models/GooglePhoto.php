<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GooglePhoto extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',       // The ID of the user who owns the photo
        'photo_id',      // The unique photo ID from Google Photos
        'photo_url',     // The URL of the photo in Google Photos
        'user_name',  
    ];
}
