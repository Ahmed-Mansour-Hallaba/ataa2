<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public $timestamps = false;
    public function volunteers()
    {
        return $this->hasMany('App\Models\Volunteer');
    }
    public function acheivers()
    {
        return $this->hasManyThrough('App\Models\Volunteer', 'App\Models\Achievment');
    }
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

}
