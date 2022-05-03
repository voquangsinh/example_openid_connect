<?php

namespace App\Core\Actions;

use App\Core\ExternalRequest\GoogleProviderRequest;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Redis;

class UserProfileAction
{
    /**
     * Creted user profile
     *
     * @param int    $userId user id
     * @param string $code   code
     *
     * @return void
     */
    public function createUserProfile(array $data)
    {
        return UserProfile::create($data);
    }
}
