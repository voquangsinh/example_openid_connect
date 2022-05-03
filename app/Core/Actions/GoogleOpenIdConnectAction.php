<?php

namespace App\Core\Actions;

use App\Core\ExternalRequest\GoogleProviderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class GoogleOpenIdConnectAction
{
    /**
     * Reqeuest token to provider
     *
     * @param int    $userId user id
     * @param string $code   code
     *
     * @return void
     */
    public function requestToken(int $userId, string $code)
    {
        $res = app(GoogleProviderRequest::class)
                ->setUri('https://oauth2.googleapis.com/token')
                ->setMethod('POST')
                ->setParamsRequet([
                    'code' => $code,
                    'client_id' => config('oidc.google.client_id'),
                    'client_secret' => config('oidc.google.secret'),
                    'redirect_uri' => config('oidc.google.callback'),
                    'grant_type' => 'authorization_code'
                ])
                ->call();

        $claims = json_decode(base64_decode(explode(".", $res['id_token'])[1]));

        if (! $this->checkValidNonce($claims->nonce)) {
            return false;
        }

        $userProfile = app(UserProfileAction::class)->createUserProfile(['user_id' => explode('.', $claims->nonce)[0], 'avatar' => $claims->picture]);
        $openidInfo = app(OpenidConnectInfomationAction::class)->createOpenidInfomation([
            'user_id' => explode('.', $claims->nonce)[0],
            'access_token' => $res['access_token'],
            'token_type' => $res['token_type']
        ]);

        return true;
    }

    /**
     * Check valid nonce
     *
     * @param string $nonce nonce
     *
     * @return bool
     */
    public function checkValidNonce($nonce): bool
    {
        list($userId, $nonce) = explode('.', $nonce);
        return $nonce === Redis::get('nonce_' . $userId);
    }

    /**
     * Get params connect
     *
     * @return array
     */
    public function getParamsConnect(int $userId = 0): array
    {
        $userId = $userId ?? Auth::user()->id;
        $state = Str::random(30);
        $none = Str::random(30);

        Redis::set('state_' . $userId, $state, 'EX', 300);
        Redis::set('nonce_' . $userId, $none, 'EX', 300);

        $params = [
            'client_id' => config('oidc.google.client_id'),
            'response_type' => 'code',
            'scope' => 'profile',
            'redirect_uri' => config('oidc.google.callback'),
            'state' => implode('.', [$userId, $state]),
            'nonce' => implode('.', [$userId, $none]),
            'display' => 'popup',
        ];

        return $params;
    }
}
