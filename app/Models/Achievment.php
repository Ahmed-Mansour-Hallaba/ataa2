<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievment extends Model
{
    public $timestamps = false;
    public function volunteer()
    {
        return $this->belongsTo('App\Models\Volunteer');
    }

    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

}
