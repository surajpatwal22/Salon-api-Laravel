<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable=['photo','status','title', 'sub_title'];

    protected $appends = ['image_data'];

    public function getImageDataAttribute()
    {
        return url($this->photo);
    }

}
