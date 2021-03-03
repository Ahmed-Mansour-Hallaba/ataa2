<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerMinResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'mobile' => $this->mobile,
            'NID' => $this->NID,
            'img' => $this->img,
            'type'=>'Volunteer',
            'status'=>$this->user->remember_token=='stopped'?'stopped':'working',
            'tags' => TagResource::collection($this->tags),

            // 'jobs' => JobResource::collection($this->jobs),

            // 'achievments' => AchievmentResource::collection($this->achievments)
        ];
    }
}
