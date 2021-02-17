<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'name'=>$this->user->name,
            'email'=>$this->user->email,
            'mobile'=>$this->mobile,
            'mobile2'=>$this->mobile2,
            'img'=>$this->img,
            'jobs'=>JobResource::collection($this->jobs),
            'type'=>'Organization'
        ];
    }
}
