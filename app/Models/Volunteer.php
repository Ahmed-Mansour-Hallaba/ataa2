<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->morphOne('App\Models\User', 'userable');
    }
    public function jobs()
    {
        return $this->belongsToMany('App\Models\Job','volunteers_jobs')->withPivot('status','stars','feedback');
    }

    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
