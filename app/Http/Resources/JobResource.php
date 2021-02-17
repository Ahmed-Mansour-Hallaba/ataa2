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
            'tag'=>$this->tag->name,
            'organization'=>$this->organization->user->name,
            'organization_logo'=>$this->organization->img,
            'organization_email'=>$this->organization->user->email,
        ];
    }
}
