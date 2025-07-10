<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin']);

Route::get('/repassword', [AuthController::class, 'showRepassword'])->name('repassword');
Route::post('/repassword', [AuthController::class, 'doRepassword']);

Route::get('/reminders', function () {
    return view('reminders');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/plans', function () {
    return view('plans');
});
Route::get('/profile/upload-avatar', function () {
    return 'Đây là trang upload-avatar, chỉ xử lý POST mới có tác dụng.';
});

Route::post('/profile/upload-avatar', [AuthController::class, 'uploadAvatar'])->name('profile.upload_avatar');
Route::get('/profile/upload-anhbia', function () {
    return 'Đây là trang upload-anhbia, chỉ xử lý POST mới có tác dụng.';
});

Route::post('/profile/upload-anhbia', [AuthController::class, 'uploadAnhBia'])->name('profile.upload_anhbia');

Route::post('/profile/update', [AuthController::class, 'update'])->name('profile.update');


Route::get('/logout', function () {
    session()->forget('user');
    return redirect('/')->with('success', 'Đã đăng xuất');
});

?>