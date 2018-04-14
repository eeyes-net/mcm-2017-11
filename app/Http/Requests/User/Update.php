<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'contact' => 'numeric',
            'email' => 'email',
            'experience' => 'string|max:4096',
            'coach_name' => 'string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'contact' => '联系电话',
            'coach_name' => '教练姓名',
            'experience' => '参赛与获奖经历',
        ];
    }
}
