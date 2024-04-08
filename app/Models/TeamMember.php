<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'image','designation','status'
    ];

    protected $appends = ['image_data'];

    public function getImageDataAttribute()
    {
        return url($this->image);
    }
}
