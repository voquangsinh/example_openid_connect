<?php

namespace App\Core\Actions;

use App\Core\ExternalRequest\GoogleProviderRequest;
use App\Models\OpenidConnectInfomation;
use Illuminate\Support\Facades\Redis;

class OpenidConnectInfomationAction
{
    /**
     * Reqeuest token to provider
     *
     * @param int    $userId user id
     * @param string $code   code
     *
     * @return void
     */
    public function createOpenidInfomation(array $data)
    {
       return OpenidConnectInfomation::create($data);
    }
}
