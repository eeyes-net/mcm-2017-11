<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArrayResource extends Resource
{
    public function toArray($request)
    {
        if (is_object($this->resource) && method_exists($this->resource, 'toArray')) {
            return parent::toArray($request);
        }
        return $this->resource;
    }
}
