<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    public $timestamps = false;
    public function volunteers()
    {
        return $this->belongsToMany('App\Models\Volunteer','volunteers_jobs')->withPivot('status','stars','feedback');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag');
    }

}
