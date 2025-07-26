<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\ReminderController;
use App\Models\KeHoach;
use Illuminate\Support\Facades\Route;

// Trang welcome
Route::get('/', fn () => view('welcome'))->name('welcome');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'doRegister']);
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin']);

Route::get('/repassword', [AuthController::class, 'showRepassword'])->name('repassword');
Route::post('/repassword', [AuthController::class, 'doRepassword']);

Route::get('/leader', [LeaderController::class, 'showLeader'])->name('showLeader');
Route::post('/search_members', [LeaderController::class, 'search_members'])->name('search_members');
Route::post('/addgroup', [LeaderController::class, 'addgroup'])->name('addgroup');
Route::post('/invite', [LeaderController::class, 'invite'])->name('invite');

Route::get('/group', [LeaderController::class, 'showGroup'])->name('showGroup');
Route::post('/group', [LeaderController::class, 'doGroup'])->name('doGroup');
Route::post('/groups', [LeaderController::class, 'doGroups'])->name('doGroups');


Route::get('/reminders', function () {
    return view('reminders');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Reminders (ví dụ)
Route::get('/reminders', fn () => view('reminders'))->name('reminders');

Route::get('/plans', function () {
    return view('plans');
});

// Kế hoạch – yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    // Kế hoạch
    Route::get('/ke-hoach', [PlansController::class, 'index'])->name('plans.index');
    Route::post('/ke-hoach', [PlansController::class, 'store'])->name('plans.store');
    Route::delete('/ke-hoach/{id}', [PlansController::class, 'deletePlan'])->name('plans.delete');
    Route::put('/ke-hoach/{id}', [PlansController::class, 'updatePlan'])->name('plans.update');

    // Công việc
    Route::post('/cong-viec', [PlansController::class, 'storeTask'])->name('tasks.store');
    Route::delete('/cong-viec/{id}', [PlansController::class, 'destroyTask'])->name('tasks.delete');
    Route::put('/cong-viec/{id}', [PlansController::class, 'updateTask'])->name('tasks.update');

    // Mục công việc
    Route::post('/muc-cong-viec', [PlansController::class, 'storeSubtask'])->name('subtasks.store');
    Route::put('/muc-cong-viec/{id}', [PlansController::class, 'updateSubtask'])->name('subtasks.update');
    Route::delete('/muc-cong-viec/{id}', [PlansController::class, 'deleteSubtask'])->name('subtasks.delete');

    Route::put('/muc-cong-viec/{id}/sua', [PlansController::class, 'updateSubtask1'])->name('subtasks.update1');
    Route::post('/tasks/update-priority/{id}', [PlansController::class, 'updateTaskPriority']);

    // Load công việc HTML
    Route::get('/ke-hoach/{id}/cong-viec', [PlansController::class, 'renderCongViecHtml'])->name('plans.tasks.html');
    Route::post('/api/muc-cong-viec/{id}/toggle-status', [PlansController::class, 'toggleStatus']);
    Route::put('/subtasks/{id}', [PlansController::class, 'updateSubtask'])->name('subtasks.update');
    Route::put('/subtasks/{id}', [PlansController::class, 'updateSubtask1'])->name('subtasks.update');
    Route::delete('/tasks/{id}', [PlansController::class, 'destroyTask'])->name('tasks.delete');
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

// reminders (nhacnho - nguyenthaianh)
Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders');

Route::middleware('auth')->group(function () {
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders');
});

Route::post('/reminders/set', [ReminderController::class, 'set'])->name('reminders.set');

Route::patch('/reminders/update/{id}', [ReminderController::class, 'update'])->name('reminders.update');
Route::delete('/reminders/delete/{id}', [ReminderController::class, 'delete'])->name('reminders.delete');
Route::get('/reminders/deadline/{id}', [ReminderController::class, 'getDeadlineByCauHinh']);
?>
