<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Post extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->when(!is_null($this->content), $this->content),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
