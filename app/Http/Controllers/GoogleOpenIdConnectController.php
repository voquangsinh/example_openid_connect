<?php

namespace App\Http\Controllers;

use App\Core\Actions\GoogleOpenIdConnectAction;
use App\Models\OpenidConnectInfomation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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
        list($isConnected, $params) = app(GoogleOpenIdConnectAction::class)->getParamsConnect($user);

        return view('setting', ['auth' => $user, 'isConnected' => $isConnected, 'params' => $params]);
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
        if ($state !== Redis::get('state_google_' . $userId)) {
            return;
        }
        try {
            app(GoogleOpenIdConnectAction::class)->requestToken($userId, $params['code']);
            return redirect(route('setting'));
        } catch (\Exception $e) {
            return redirect(route('setting'));
        }
    }
}
