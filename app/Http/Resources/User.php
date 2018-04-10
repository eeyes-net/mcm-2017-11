<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'stu_id' => $this->stu_id,
            'department' => $this->department,
            'major' => $this->major,
            'class' => $this->class,
            'contact' => $this->contact,
            'email' => $this->email,
            'experience' => $this->experience,
            'coach_name' => $this->coach_name,
            'group' => $this->group,
            'position' => $this->whenPivotLoaded('team_user', function () {
                return $this->pivot->position;
            }),
            // 'status' => $this->whenPivotLoaded('team_user', function () {
            //     return $this->pivot->status;
            // }),
        ];
    }
}
