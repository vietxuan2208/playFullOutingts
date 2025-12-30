<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * CHƯA ĐĂNG NHẬP → ĐÁ VỀ LOGIN + MESSAGE
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Nếu là API (ajax/json)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Lưu URL trước đó để login xong quay lại
        session(['url.intended' => url()->previous()]);

        // Redirect login + message
        return redirect()->route('login')
            ->with('error', 'Login To Continute');
    }
}
