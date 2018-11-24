<?php

namespace App\Http\Requests\ResetDb;

use App\Libraries\ResetDb;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;

class Reset extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if ($this->post('confirm') !== '我确认重置数学建模网站数据库') {
                $validator->errors()->add('confirm', '必须输入“我确认重置数学建模网站数据库”');
            }
            if (!Session::has('admin_authorization')) {
                $validator->errors()->add('login', '无法获取到您的权限信息，请刷新页面');
            }
        });
        $validator->after(function (Validator $validator) {
            if (!ResetDb::canResetDb(Session::get('admin_authorization'))) {
                $validator->errors()->add('permission', '您没有重置数据库的权限');
            }
        });
    }
}
