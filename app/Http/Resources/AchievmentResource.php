<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AchievmentResource extends JsonResource
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
            'feedback'=>$this->feedback,
            'stars'=>$this->stars,
            'media'=>$this->media,
            // 'volunteer_id'=>$this->volunteer_id,
            // 'volunteer_name'=>$this->volunteer->user->name,
            // 'volunteer_name'=>$this->volunteer->name,
            'job_id'=>$this->job_id,
            'job_name'=>$this->job->name,
            'job_description'=>$this->job->description,
            'job_tag'=>$this->job->tag->name,
            'job_organization'=>$this->job->organization->user->name,
            'job_organization_logo'=>$this->job->organization->img,
            'organization_email'=>$this->organization->user->email,
        ];
    }
}
