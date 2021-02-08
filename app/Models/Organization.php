<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;
    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }
    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
