<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageVideo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_url',
        'video',
        'thumbnail',
        'status',
    ];
}