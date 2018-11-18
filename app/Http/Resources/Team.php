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
            'number' => empty($this->number) ? '未分配' : $this->number,
            'users' => User::collection($this->whenLoaded('users')),
            'matches' => Match::collection($this->whenLoaded('matches')),
        ];
    }
}
