<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('categoriesList', Category::where('is_delete', 0)->orderBy('id', 'asc')->get());

            // Lấy category hiện tại từ route nếu có
            $currentCategoryId = optional(Route::current())->parameter('id');
            $currentCategory = null;
            if ($currentCategoryId) {
                $currentCategory = Category::find($currentCategoryId);
            }

            $view->with('category', $currentCategory);
        });

       
    }
}
