<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    public function organizations()
    {
        return $this->morphedByMany('App\Models\Organization', 'taggable');
    }

    public function volunteers()
    {
        return $this->morphedByMany('App\Models\Volunteer', 'taggable');
    }
}
