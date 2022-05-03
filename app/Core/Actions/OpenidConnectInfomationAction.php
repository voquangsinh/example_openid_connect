<?php

namespace App\Core\Actions;

use App\Models\OpenidConnectInfomation;

class OpenidConnectInfomationAction
{
    /**
     * Reqeuest token to provider
     *
     * @param array $data data
     *
     * @return OpenidConnectInfomation
     */
    public function createOpenidInfomation(array $data)
    {
       return OpenidConnectInfomation::create($data);
    }

    /**
     * Get openid connect infomation by conditions
     *
     * @param mixed $conditions conditions
     *
     * @return OpenidConnectInfomation
     */
    public function getOpenidInfomationByCondition($conditions)
    {
        return OpenidConnectInfomation::where($conditions)->get();
    }
}
