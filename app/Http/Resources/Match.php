<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Match extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'expired_at' => $this->expired_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'teams_count' => $this->when(!is_null($this->teams_count), $this->teams_count),
            'is_applied' => $this->when(!is_null($this->is_applied), $this->is_applied),
        ];
    }
}
