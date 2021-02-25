<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MinJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'end_date'=>$this->end_date,
            'registration_date'=>$this->registration_date,
            'city'=>$this->city->name,
            'tag'=>$this->tag->name,
            'is_ended'=>$this->end_date<Carbon::today()?'ended':'working',
            'can_register'=>$this->registration_date<Carbon::today()?'ended':'working',
            'volunteers_count'=> count($this->volunteers()->where('status','!=','rejected')->get()),
            // 'description'=>substr($this->description,0,20),
            'organization'=>$this->organization->user->name,
            'img'=>$this->media

        ];
    }
}
