<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class ResetDb
{
    public static function getRedirectUrl()
    {
        return config('eeyes.account.url') . '/oauth/authorize?' . http_build_query([
                'client_id' => config('eeyes.account.admin_app.id'),
                'redirect_uri' => route('admin_login_callback'),
                'response_type' => 'code',
                'scope' => 'permission.read info-username.read',
            ]);
    }

    public static function canResetDb($admin_authorization)
    {
        $client = new Client;
        $response = $client->get(config('eeyes.account.url') . '/api/user/permission/can', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $admin_authorization,
            ],
            'query' => [
                'permission' => 'xjtu.website.mcm.reset_db',
            ],
        ]);
        $data = json_decode((string)$response->getBody(), true);
        if ($data['can']) {
            return true;
        }
        return false;
    }

    public static function reset()
    {
        $outputBuffer = new BufferedOutput;
        try {
            Artisan::call('migrate:fresh', [
                '--force' => true,
            ], $outputBuffer);

            Artisan::call('cache:clear', [], $outputBuffer);

            Artisan::call('mcm:reset-team-number-auto-increment', [
                '--yes' => true,
            ], $outputBuffer);
        } catch (\Exception $e) {
            return [
                'result' => false,
                'output' => $outputBuffer->fetch(),
            ];
        }
        return [
            'result' => true,
            'output' => $outputBuffer->fetch(),
        ];
    }
}
