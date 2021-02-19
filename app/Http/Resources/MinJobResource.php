<?php

namespace App\Http\Resources;

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
            // 'description'=>substr($this->description,0,20),
            'organization'=>$this->organization->user->name,
            'img'=>$this->media

        ];
    }
}
