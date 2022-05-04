<?php

namespace App\Core\Actions;

use App\Core\ExternalRequest\GoogleProviderRequest;
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
            throw new \Exception('Invalid noncce', 500);
        }
        if ($this->checkExistSub([['sub', $claims->sub, ['provider', 'google']]])) {
            throw new \Exception('Email connect exists', 500);
        }

        app(UserProfileAction::class)->createUserProfile(['user_id' => explode('.', $claims->nonce)[0], 'avatar' => $claims->picture]);
        app(OpenidConnectInfomationAction::class)->createOpenidInfomation([
            'user_id' => explode('.', $claims->nonce)[0],
            'provider' => 'google',
            'sub' => $claims->sub,
            'access_token' => $res['access_token'],
            'token_type' => $res['token_type']
        ]);

        return;
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
        return $nonce === Redis::get('none_google_' . $userId);
    }

    /**
     * Check exists sub
     *
     * @param array $conditions conditions
     *
     * @return bool
     */
    public function checkExistSub(array $conditions): bool
    {
        return (bool) app(OpenidConnectInfomationAction::class)->getOpenidInfomationByCondition($conditions)->count();
    }

    /**
     * Get params connect
     *
     * @param User $user user id
     *
     * @return array
     */
    public function getParamsConnect($user): array
    {
        $conditions = function($query) use ($user) {
            return $query->where('user_id', $user->id)
                ->whereIn('provider', array_keys(config('oidc')));
        };
        $isConnectedProvider = app(OpenidConnectInfomationAction::class)->getOpenidInfomationByCondition($conditions)->pluck('provider')->toArray();

        $params = [
            'google' => $this->getParamGoogle($user->id),
        ];

        return [$isConnectedProvider, $params];
    }

    /**
     * Get param google
     *
     * @param int $userId user id
     *
     * @return array
     */
    public function getParamGoogle($userId)
    {
        $state = Str::random(30);
        $none = Str::random(30);
        Redis::set('state_google_' . $userId, $state, 'EX', 300);
        Redis::set('none_google_' . $userId, $none, 'EX', 300);
        return [
            'client_id' => config('oidc.google.client_id'),
            'response_type' => 'code',
            'scope' => 'profile',
            'redirect_uri' => config('oidc.google.callback'),
            'state' => implode('.', [$userId, $state]),
            'nonce' => implode('.', [$userId, $none]),
            'display' => 'popup',
        ];
    }
}
