<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            // Lưu URL trước đó để login xong quay lại
            session(['url.intended' => url()->previous()]);

            // Redirect login + message
            return route('login');
        }
    }
}
