<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name','category_image','slug'
    ];
    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
