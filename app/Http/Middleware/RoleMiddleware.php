<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // ❌ Chưa login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        $user = Auth::user();

        // Truyền role dạng số (role_id)
        if (is_numeric($role)) {
            if ($user->role_id != (int) $role) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Bạn không có quyền truy cập');
            }
        }
        // Truyền role dạng tên
        else {
            $userRole = strtolower($user->role->name ?? '');

            // admin → cho phép cả admin & superadmin
            if ($role === 'admin') {
                if (!in_array($userRole, ['admin', 'superadmin'])) {
                    Auth::logout();
                    return redirect()->route('login')
                        ->with('error', 'Bạn không có quyền truy cập');
                }
            }
            // các role khác (user, superadmin)
            else {
                if ($userRole !== strtolower($role)) {
                    Auth::logout();
                    return redirect()->route('login')
                        ->with('error', 'Bạn không có quyền truy cập');
                }
            }
        }

        return $next($request);
    }
}
