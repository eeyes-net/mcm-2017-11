<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetDb\Reset;
use App\Libraries\ResetDb;
use Illuminate\Http\Request;

class ResetDbController extends Controller
{
    public function check(Request $request)
    {
        if (!$request->session()->has('admin_authorization')) {
            $request->session()->put('url.intended', '/admin/reset_db');
            return [
                'login' => false,
                'permission' => false,
                'redirect' => ResetDb::getRedirectUrl(),
            ];
        }
        if (!ResetDb::canResetDb($request->session()->get('admin_authorization'))) {
            return [
                'login' => true,
                'permission' => false,
            ];
        }
        return [
            'login' => true,
            'permission' => true,
        ];
    }

    public function reset(Reset $request)
    {
        return ResetDb::reset();
    }
}
