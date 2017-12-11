<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OAuthLoginController extends Controller
{
    public function login()
    {
        return redirect(env('EEYES_ACCOUNT_URL') . '/oauth/authorize?' . http_build_query([
                'client_id' => env('EEYES_ACCOUNT_APP_ID'),
                'redirect_uri' => url('/oauth/callback'),
                'response_type' => 'code',
                'scope' => 'info-username.read info-user_id.read info-name.read info-email.read info-email.write info-mobile.read info-school.read',
            ]));
    }

    public function loginAdmin()
    {
        Session::put('get_permission', true);
        return redirect(env('EEYES_ACCOUNT_URL') . '/oauth/authorize?' . http_build_query([
                'client_id' => env('EEYES_ACCOUNT_APP_ID'),
                'redirect_uri' => url('/oauth/callback'),
                'response_type' => 'code',
                'scope' => 'permission.read info-username.read',
            ]));
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            throw new CustomException($request->get('error'));
        }

        $client = new Client;

        $response = $client->post(env('EEYES_ACCOUNT_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('EEYES_ACCOUNT_APP_ID'),
                'client_secret' => env('EEYES_ACCOUNT_APP_SECRET'),
                'redirect_uri' => url('/oauth/callback'),
                'code' => $request->code,
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);

        $authorization = $data['token_type'] . ' ' . $data['access_token'];

        $response = $client->get(env('EEYES_ACCOUNT_URL') . '/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $authorization,
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);

        $username = $data['username'];
        $user = User::where('username', $username)->first();
        if (!$user) {
            $user = new User([
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
            $user->save();
        }
        Auth::login($user);

        if ($user->group !== 'admin' && Session::pull('get_permission')) {
            $response = $client->get(env('EEYES_ACCOUNT_URL') . '/api/user/permission/can', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $authorization,
                ],
                'query' => [
                    'permission' => 'xjtu.website.mcm.admin',
                ],
            ]);
            $data = json_decode((string)$response->getBody(), true);
            if ($data['can']) {
                $user->group = 'admin';
                $user->save();
            } else {
                throw new AuthorizationException('抱歉，您不是管理员。');
            }
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/');
    }
}
