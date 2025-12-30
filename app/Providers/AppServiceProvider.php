<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        view()->composer('layouts.user.user', function ($view) {

            // 🔹 Danh mục
            $categories = Category::all();

            // 🔹 Đếm USER online (không tính admin)
            $onlineUsers = DB::table('sessions')
                ->join('users', 'users.id', '=', 'sessions.user_id')
                ->where('users.role_id', 1)
                ->where('sessions.last_activity', '>=', now()->subMinutes(5)->timestamp)
                ->distinct('sessions.user_id')
                ->count('sessions.user_id');

            $view->with([
                'categories'  => $categories,
                'onlineUsers' => $onlineUsers,
            ]);
        });
    }
}
