<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSlidersOne extends Model
{
    use HasFactory;

    protected $table = 'new_sliders_one'; // Laravel-কে বলুন যে এটি ব্যবহার করতে হবে।

    protected $fillable = [
        'image', 'url', 'status', 'is_feature', 'is_pop', 'is_sub'
    ];
}
