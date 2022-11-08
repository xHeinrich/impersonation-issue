<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
    private StatefulGuard $guard;
    private AuthFactory $auth;

    public function __construct(StatefulGuard $guard, AuthFactory $auth)
    {
        $this->guard = $guard;
        $this->auth = $auth;
    }

    public function impersonate(Request $request, User $user)
    {
        $request->session()->put([
            'password_hash_' . $this->auth->getDefaultDriver() => $user->getAuthPassword()
        ]);

        $this->guard->login($user);

        return response([
            'yo' => 'logged in as ' . auth()->user()->name
        ]);
    }
}
