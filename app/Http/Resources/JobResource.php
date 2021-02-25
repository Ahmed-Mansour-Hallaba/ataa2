<?php

namespace App\Http\Resources;

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
            'img'=>$this->media,
            'tag'=>$this->tag->name,
            'city'=>$this->city->name,
            'organization_id'=>$this->organization_id,
            'organization'=>$this->organization->user->name,
            'organization_logo'=>$this->organization->img,
            'organization_email'=>$this->organization->user->email,
            'stars' => $this->whenPivotLoaded('volunteers_jobs', function () {
                return $this->pivot->stars;
            }),
        ];
    }
}
