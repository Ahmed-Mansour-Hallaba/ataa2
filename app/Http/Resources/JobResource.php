<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'is_ended'=>$this->end_date<Carbon::today()?'ended':'working',
            'can_register'=>$this->registration_date<Carbon::today()?'ended':'working',
            'img'=>$this->media,
            'tag'=>$this->tag->name,
            'city'=>$this->city->name,
            'organization_id'=>$this->organization_id,
            'organization'=>$this->organization->user->name,
            'organization_logo'=>$this->organization->img,
            'organization_email'=>$this->organization->user->email,
            'organization_mobile'=>$this->organization->user->mobile,
            'stars' => $this->whenPivotLoaded('volunteers_jobs', function () {
                return $this->pivot->stars;
            }),
            'status' => $this->whenPivotLoaded('volunteers_jobs', function () {
                return $this->pivot->status;
            }),
        ];
    }
}
