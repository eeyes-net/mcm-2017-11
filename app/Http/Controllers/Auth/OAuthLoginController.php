<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomException;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OAuthLoginController extends LoginController
{
    public function login()
    {
        if (!Session::has('authorization')) {
            return redirect(config('eeyes.account.url') . '/oauth/authorize?' . http_build_query([
                    'client_id' => config('eeyes.account.app.id'),
                    'redirect_uri' => route('login_callback'),
                    'response_type' => 'code',
                    'scope' => implode(' ', [
                        'info-username.read',
                        'info-user_id.read',
                        'info-name.read',
                        'info-email.read',
                        'info-email.write',
                        'info-mobile.read',
                        'info-mobile.write',
                        'info-school.read',
                    ]),
                ]));
        }

        $client = new Client;
        $response = $client->get(config('eeyes.account.url') . '/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => Session::get('authorization'),
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);

        $username = $data['username'];
        $user = User::where('username', $username)->first();
        if (!$user) {
            $user = User::create([
                'username' => $username,
                'stu_id' => $data['user_id'],
                'name' => $data['name'],
                'department' => $data['dep'],
                'major' => $data['speciality'],
                'class' => $data['classid'],
                'contact' => $data['mobile'],
                'email' => $data['email'],
                'password' => '*',
                'group' => 'student', // default as student
            ]);
        }
        Auth::login($user);
        return redirect()->intended('/');
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            throw new CustomException($request->get('error'));
        }
        try {
            $client = new Client;
            $response = $client->post(config('eeyes.account.url') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('eeyes.account.app.id'),
                    'client_secret' => config('eeyes.account.app.secret'),
                    'redirect_uri' => route('login_callback'),
                    'code' => $request->get('code'),
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            Session::put('authorization', $data['token_type'] . ' ' . $data['access_token']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $request->toArray());
        }
        return redirect(route('login'));
    }

    public function adminLogin()
    {
        if (!Auth::check()) {
            return redirect(route('login'));
        }
        $user = Auth::user();
        if ($user->group === 'admin') {
            return redirect()->intended('/admin');
        }
        if (!Session::has('admin_authorization')) {
            return redirect(config('eeyes.account.url') . '/oauth/authorize?' . http_build_query([
                    'client_id' => config('eeyes.account.admin_app.id'),
                    'redirect_uri' => route('admin_login_callback'),
                    'response_type' => 'code',
                    'scope' => 'permission.read info-username.read',
                ]));
        }
        $client = new Client;
        $response = $client->get(config('eeyes.account.url') . '/api/user/permission/can', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => Session::get('admin_authorization'),
            ],
            'query' => [
                'permission' => 'xjtu.website.mcm.admin',
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);
        if (!$data['can']) {
            abort(401, '抱歉，您不是管理员。');
        }
        $user->group = 'admin';
        $user->save();
        return redirect()->intended('/admin');
    }

    public function adminCallback(Request $request)
    {
        if ($request->has('error')) {
            throw new CustomException($request->get('error'));
        }
        try {
            $client = new Client;
            $response = $client->post(config('eeyes.account.url') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('eeyes.account.admin_app.id'),
                    'client_secret' => config('eeyes.account.admin_app.secret'),
                    'redirect_uri' => route('admin_login_callback'),
                    'code' => $request->get('code'),
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            Session::put('admin_authorization', $data['token_type'] . ' ' . $data['access_token']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $request->toArray());
        }
        return redirect(route('admin_login'));
    }
}
