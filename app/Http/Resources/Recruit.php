<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Recruit extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'members' => $this->members,
            'tags' => explode(',', $this->tags),
            'description' => $this->description,
            'contact' => $this->contact,
            'created_at' => $this->created_at,
            'team_id' => $this->team_id,
            'team' => new Team($this->whenLoaded('team')),
        ];
    }
}
