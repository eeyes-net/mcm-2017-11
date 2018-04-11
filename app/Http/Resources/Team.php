<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Team extends Resource
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
            'team_id' => $this->team_id,
            'users' => User::collection($this->whenLoaded('users')),
        ];
    }
}
