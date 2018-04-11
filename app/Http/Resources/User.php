<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->when(!is_null($this->username), $this->username),
            'name' => $this->name,
            'stu_id' => $this->stu_id,
            'department' => $this->when(!is_null($this->department), $this->department),
            'major' => $this->when(!is_null($this->major), $this->major),
            'class' => $this->when(!is_null($this->class), $this->class),
            'contact' => $this->contact,
            'email' => $this->email,
            'experience' => $this->when(!is_null($this->experience), $this->experience),
            'coach_name' => $this->when(!is_null($this->coach_name), $this->coach_name),
            'group' => $this->when(!is_null($this->group), $this->group),
            'position' => $this->whenPivotLoaded('team_user', function () {
                return $this->pivot->position;
            }),
            // 'status' => $this->whenPivotLoaded('team_user', function () {
            //     return $this->pivot->status;
            // }),
        ];
    }
}
