<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }
    public function jobs()
    {
        return $this->hasMany('App\Models\Job');
    }

    public function achievments()
    {
        return $this->hasMany('App\Models\Achievment');
    }
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
