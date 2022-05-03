<?php

namespace App\Services;

use App\Core\ExternalRequest\GoogleProviderRequest;

class GoogleOpenIdConnectService
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
        dd($res);
    }
}
