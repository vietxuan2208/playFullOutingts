<?php

use App\Http\Controllers\User\AboutusController;


use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RecycleCategoryController;
use App\Http\Controllers\Admin\RecycleGameController;
use App\Http\Controllers\Admin\RecycleUserController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SetPasswordController;


use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryLocationController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\ItineraryAdminController;
use App\Http\Controllers\User\BlogUserController;
use App\Http\Controllers\User\ChatController as UserChatController;
use App\Http\Controllers\User\ContactController as UserContactController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\GameController as UserGameController;
use App\Http\Controllers\User\ItineraryController as UserItineraryController;
use App\Http\Controllers\Admin\ChartController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', [DashboardController::class, 'dashboard'])->name('user.dashboard');

Route::get('user/dashboard', [DashboardController::class, 'dashboard'])->name('user.dashboard');



// Route hiển thị giao diện quản lý sản phẩm (admin - tĩnh)
Route::get('/product', function () {
    return view('admin.product');
})->name('product_admin'); // Đặt tên route là product_admin

// -------------------- QUẢN LÝ SẢN PHẨM (ADMIN) --------------------

use App\Http\Controllers\Admin\ProductController;

Route::get('/admin/product', [ProductController::class, 'index'])->name('product_admin');

Route::post('/admin/product/store', [ProductController::class, 'store'])->name('product_admin.store');

Route::post('/admin/product/update', [ProductController::class, 'update'])->name('admin.product.update');
Route::post('/admin/product/delete', [ProductController::class, 'delete'])->name('admin.product.delete');

//--------------------Quản lý Order-----------------------------
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RecycleCategoryLocationController;
use App\Http\Controllers\Admin\RecycleItineraryController;
use App\Http\Controllers\Admin\RecycleLocationController;
use App\Http\Controllers\Admin\RecycleMaterialController;
use App\Http\Controllers\Admin\RecycleProductController;

Route::get('/admin/orderPending', [OrderController::class, 'pending'])->name('orderPending_admin');
Route::get('/admin/orderShipped', [OrderController::class, 'shipped'])->name('orderShipped_admin');
Route::get('/admin/orderDelivered', [OrderController::class, 'delivered'])->name('orderDelivered_admin');
Route::get('/admin/orderCanceled', [OrderController::class, 'canceled'])->name('orderCanceled_admin');
Route::post('/admin/order/update-status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');

//--------------------Quản lý client-----------------------------
// Route hiển thị detail
use App\Http\Controllers\User\ProductController as UserProductController;

Route::get('/user/detail/{id}', [UserProductController::class, 'detail'])->name('user_detail');

// Route hiển thị Shop
Route::get('/user/shop', [UserProductController::class, 'shop'])->name('user_shop');

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\GameUserController;
use App\Http\Controllers\User\LocationController as UserLocationController;

// --------------------Quản lý cart-----------------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart_user');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

//--------------------Quản lý profile-----------------------------
use App\Http\Controllers\User\UserController as ClientUserController;

Route::get('/profile', [ClientUserController::class, 'profile'])->name('user.profile');
Route::post('/profile/update', [ClientUserController::class, 'updateProfile'])->name('user.profile.update');

//--------------------Quản lý Checkout-----------------------------

Route::get('/checkout', [CartController::class, 'checkout'])
    ->middleware('auth') // ⛔ Chặn nếu chưa login
    ->name('checkout_user');
Route::post('/checkout', [CartController::class, 'store'])->name('checkout.store');

// ====================== PayPal Routes ======================
use App\Http\Controllers\User\PayPalController;

Route::post('/paypal/create-order', [PayPalController::class, 'createOrder'])
    ->name('paypal.createOrder')
    ->middleware('auth');

Route::post('/paypal/capture-order', [PayPalController::class, 'captureOrder'])
    ->name('paypal.captureOrder')
    ->middleware('auth');





Route::get('/user/game', [UserGameController::class, 'game'])->name('user.game');
Route::get('/user/detailGame/{slug}', [UserGameController::class, 'detailGame'])->name('user.detailGame');
Route::get('/user/game/category/{slug}', [UserGameController::class, 'categoryGame'])
    ->name('user.categoryGame');


Route::get('user/aboutus', [AboutusController::class, 'aboutus'])->name('user.aboutus');
Route::get('user/itinerary', [UserItineraryController::class, 'itinerary'])->name('user.itinerary');
Route::get('user/detailItinerary', [UserItineraryController::class, 'detailItinerary'])->name('user.detailItinerary');
Route::get('user/contact', [UserContactController::class, 'contact'])->name('user.contact');



Route::get('/games/category/{id}', [GameUserController::class, 'category'])
    ->name('games.category');

    Route::get('/location/{id}', [UserLocationController::class, 'detail'])->name('user.location.detail');


    Route::post('user/contact', [UserContactController::class, 'send'])->name('user.contact.send');
    // Endpoint to return a fresh image captcha (base64)
    Route::get('user/contact/captcha', [UserContactController::class, 'captchaImage'])->name('user.contact.captcha');


    Route::get('/user/game', [GameUserController::class, 'game'])->name('user.game');
    Route::get('/games/category/{id}', [GameUserController::class, 'category'])->name('games.category');
    Route::get('/games/detail/{id}', [GameUserController::class, 'detailGame'])->name('games.detail');
        


    Route::get('user/aboutus', [AboutusController::class, 'aboutus'])->name('user.aboutus');
    Route::get('user/itinerary', [UserItineraryController::class, 'itinerary'])->name('user.itinerary');
    Route::get('user/itinerary/{id}', [UserItineraryController::class, 'itineraryDetail'])->name('user.itinerary.detail');
    Route::get('user/contact', [UserContactController::class, 'contact'])->name('user.contact');

    // Blog pages for user
    Route::get('/blog', [BlogUserController::class, 'index'])->name('user.blog.index');
    Route::get('/blog/{id}', [BlogUserController::class, 'show'])->name('user.blog.show');


   

// Authentication
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected dashboards
Route::middleware(['role:user', 'check.password'])->group(function () {
    // Route::get('user/dashboard', function () {
    //     return view('user.dashboard');
    // })->name('user.dashboard');
    
    // Set password for Google OAuth users
    Route::get('set-password', [SetPasswordController::class, 'show'])->name('set-password.show');
    Route::post('set-password', [SetPasswordController::class, 'store'])->name('set-password.store');
});

Route::middleware(['role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('admin/contact', [AdminContactController::class, 'index'])->name('admin.contact');
    Route::put('admin/contact/{id}/status', [AdminContactController::class, 'updateStatus'])->name('admin.contact.updateStatus');
    Route::post('admin/contact/{id}/reply', [AdminContactController::class, 'reply'])->name('admin.contact.reply');
    
    Route::get('admin/user', [UserController::class, 'user'])->name('admin.user');
    Route::put('admin/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::put('/admin/user/{user}/block', [UserController::class, 'block'])->name('admin.user.block');
    Route::put('/admin/user/{user}/unblock', [UserController::class, 'unBlock'])->name('admin.user.unblock');
    Route::post('admin/user/add', [UserController::class, 'store'])->name('admin.user.store');

    Route::get('admin/trashUser', [RecycleUserController::class, 'trash'])->name('admin.trashUser');
    Route::post('admin/recycle-user/restore/{id}', [RecycleUserController::class, 'restore'])->name('admin.recycleUser.restore');
    Route::delete('admin/recycle-user/delete/{id}', [RecycleUserController::class, 'delete'])->name('admin.recycleUser.delete');

    Route::get('admin/trashLocation', [RecycleLocationController::class, 'trash'])->name('admin.trashLocation');
    Route::delete('admin/recycle-location/delete/{id}',[RecycleLocationController::class, 'forceDelete'])->name('admin.recycleLocation.delete');
    Route::post('admin/recycle-location/restore/{id}', [RecycleLocationController::class, 'restore'])->name('admin.recycleLocation.restore');

    Route::get('admin/trashProduct', [RecycleProductController::class, 'trash'])->name('admin.trashProduct');
   Route::delete('/admin/recycle-product/delete/{id}', [RecycleProductController::class, 'delete'])
    ->name('admin.recycleProduct.delete');
    Route::post('admin/recycle-product/restore/{id}', [RecycleProductController::class, 'restore'])->name('admin.recycleProduct.restore');


    Route::get('admin/category', [CategoryController::class, 'category'])->name('admin.category');
    Route::post('admin/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('admin/category/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');

    Route::get('admin/category', [CategoryController::class, 'category'])->name('admin.category');
    Route::post('admin/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('admin/category/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');

    Route::get('admin/categoryLocation', [CategoryLocationController::class, 'categoryLocation'])->name('admin.categoryLocation');
    Route::post('admin/categoryLocation/store', [CategoryLocationController::class, 'store'])->name('admin.categoryLocation.store');
    Route::put('/admin/categoryLocation/{id}', [CategoryLocationController::class, 'update'])->name('admin.categoryLocation.update');
    Route::delete('admin/categoryLocation/{id}', [CategoryLocationController::class, 'delete'])->name('admin.categoryLocation.delete');


    Route::get('admin/trashCategoryLocation', [RecycleCategoryLocationController::class, 'trash'])->name('admin.trashCategoryLocation');
    Route::delete('admin/recycle-category-location/delete/{id}', [RecycleCategoryLocationController::class, 'delete'])->name('admin.recycleCategoryLocation.delete');
    Route::post('admin/recycle-category-location/restore/{id}', [RecycleCategoryLocationController::class, 'restore'])->name('admin.recycleCategoryLocation.restore');

    Route::get('admin/trashCategory', [RecycleCategoryController::class, 'trash'])->name('admin.trashCategory');
    Route::delete('admin/recycle-category/delete/{id}', [RecycleCategoryController::class, 'delete'])->name('admin.recycleCategory.delete');
    Route::post('admin/recycle-category/restore/{id}', [RecycleCategoryController::class, 'restore'])->name('admin.recycleCategory.restore');

    Route::get('admin/game', [GameController::class, 'game'])->name('admin.game');
    Route::post('admin/game/add', [GameController::class, 'add'])->name('admin.game.add');
    Route::put('admin/game/{id}', [GameController::class, 'update'])->name('admin.game.update');
    Route::delete('admin/game/{id}', [GameController::class, 'delete'])->name('admin.game.delete');

    Route::get('admin/trashGame', [RecycleGameController::class, 'trash'])->name('admin.trashGame');
    Route::delete('admin/recycle-game/delete/{id}', [RecycleGameController::class, 'delete'])->name('admin.recycleGame.delete');
    Route::post('admin/recycle-game/restore/{id}', [RecycleGameController::class, 'restore'])->name('admin.recycleGame.restore');

    Route::get('admin/trashMaterial', [RecycleMaterialController::class, 'trash'])->name('admin.trashMaterial');
    Route::delete('admin/recycle-material/delete/{id}',[RecycleMaterialController::class, 'forceDelete'])->name('admin.recycleMaterial.delete');

    Route::post('admin/recycle-material/restore/{id}', [RecycleMaterialController::class, 'restore'])->name('admin.recycleMaterial.restore');

    Route::get('admin/material', [MaterialController::class, 'material'])->name('admin.material');
    Route::post('admin/material/add', [MaterialController::class, 'add'])->name('admin.material.add');
    Route::put('admin/material/{id}', [MaterialController::class, 'update'])->name('admin.material.update');
    Route::delete('admin/material/{id}', [MaterialController::class, 'delete'])->name('admin.material.delete');


    // routes/web.php
Route::get(
    '/admin/game/download/{filename}',
    [GameController::class, 'download']
)->name('admin.game.download');


    Route::get('/admin/profile/{id}', [ProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/{id}/update', [ProfileController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/admin/profile/{id}/photo', [ProfileController::class, 'updatePhoto'])->name('admin.profile.updatePhoto');
    Route::post('/admin/profile/{id}/change-password', [ProfileController::class, 'changePassword'])->name('admin.profile.changePassword');

    Route::get('admin/itineraries', [ItineraryAdminController::class, 'itinerary'])->name('admin.itineraries');
    Route::post('admin/itineraries/add', [ItineraryAdminController::class, 'add'])->name('admin.itineraries.add');
    Route::get('admin/itineraries/{id}', [ItineraryAdminController::class, 'show'])->name('admin.itineraries.show');
    Route::put('admin/itineraries/update/{id}', [ItineraryAdminController::class, 'update'])->name('admin.itineraries.update');
    Route::delete('admin/itineraries/delete/{id}', [ItineraryAdminController::class, 'delete'])->name('admin.itineraries.delete');

   
     Route::get('admin/trashItineraries', [RecycleItineraryController::class, 'trash'])->name('admin.trashItineraries');
    Route::delete('admin/recycle-itineraries/delete/{id}', [RecycleItineraryController::class, 'delete'])->name('admin.recycleItinerary.delete');
    Route::post('admin/recycle-itineraries/restore/{id}', [RecycleItineraryController::class, 'restore'])->name('admin.recycleItinerary.restore');

    Route::get('admin/locations', [LocationController::class, 'location'])->name('admin.locations');
    Route::post('admin/locations/store', [LocationController::class, 'store'])->name('admin.locations.store');
    Route::put('/admin/locations/{id}', [LocationController::class, 'update'])->name('admin.locations.update');
    Route::delete('admin/locations/{id}', [LocationController::class, 'delete'])->name('admin.locations.delete');
    // BLOG MANAGEMENT
    Route::get('admin/blog', [BlogController::class, 'index'])->name('admin.blog.index');
    Route::get('admin/blog/create', [BlogController::class, 'create'])->name('admin.blog.create');
    Route::post('admin/blog', [BlogController::class, 'store'])->name('admin.blog.store');
    Route::get('admin/blog/{id}/edit', [BlogController::class, 'edit'])->name('admin.blog.edit');
    Route::put('admin/blog/{id}', [BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('admin/blog/{id}', [BlogController::class, 'destroy'])->name('admin.blog.delete');
    Route::get('admin/blog/{id}', [BlogController::class, 'show'])->name('admin.blog.show');

    // Timeline Orders Chart
    Route::get('/chart/orders', [ChartController::class, 'orderTimeline'])
        ->name('admin.chart.orders');

    // Donut Chart: Game By Category
    Route::get('/chart/game-category', [ChartController::class, 'gameByCategory'])
        ->name('admin.chart.gameCategory');

    // Stacked Chart: Orders by Status per Day
    Route::get('/chart/order-status', [ChartController::class, 'orderByStatus'])
        ->name('admin.chart.orderStatus');

});


// Social OAuth
Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('oauth.google');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('oauth.google.callback');

// Password reset with code via email


Route::get('password/forgot', [PasswordResetController::class, 'showRequest'])->name('password.request');
Route::post('password/forgot', [PasswordResetController::class, 'sendCode'])->name('password.forgot.post');
Route::get('password/verify', [PasswordResetController::class, 'showVerify'])->name('password.verify');
Route::post('password/verify', [PasswordResetController::class, 'verifyCode'])->name('password.verify.post');
Route::get('password/reset', [PasswordResetController::class, 'showReset'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset.post');

// API gọi chatbot

Route::post('/chatbot', [UserChatController::class, 'chat']);




