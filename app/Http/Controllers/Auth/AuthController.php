<?php

namespace App\Http\Controllers\Auth;

use App\Application\Auth\HandleEveCallback;
use App\Application\Auth\RedirectToEveSSO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function redirectToEveSSO(Request $request, RedirectToEveSSO $redirectToEveSSO)
    {
        return redirect()->away($redirectToEveSSO->redirect());
    }

    public function handleEveCallback(Request $request, HandleEveCallback $handleEveCallback)
    {
        $user = $handleEveCallback->handle($request->get('code'));

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
