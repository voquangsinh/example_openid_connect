<?php

namespace App\Http\Controllers;

use App\Core\Actions\GoogleOpenIdConnectAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class GoogleOpenIdConnectController extends Controller
{
    /**
     * Open ID connect
     *
     * @return view
     */
    public function index()
    {
        $user = Auth::user();
        $state = Str::random(30);
        $none = Str::random(30);

        Redis::set('state_' . $user->id, $state, 'EX', 300);
        Redis::set('none_' . $user->id, $none, 'EX', 300);

        $params = [
            'client_id' => config('oidc.google.client_id'),
            'response_type' => 'code',
            'scope' => 'profile',
            'redirect_uri' => config('oidc.google.callback'),
            'state' => implode('.', [$user->id, $state]),
            'nonce' => implode('.', [$user->id, $none]),
            'display' => 'popup',
        ];

        return view('setting', ['auth' => $user, 'params' => $params]);
    }

    /**
     * Provider callback
     *
     * @param Request $request request
     *
     * @return redirect
     */
    public function callback(Request $request)
    {
        $params = $request->all();
        list($userId, $state) = explode('.', $params['state']);
        if ($state !== Redis::get('state_' . $userId)) {
            return;
        }
        $result = app(GoogleOpenIdConnectAction::class)->requestToken($userId, $params['code']);
        $user = User::find($userId);
        return view('setting', ['auth' => $user]);
    }
}
