<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder; // Nếu model tên Reminder
use Illuminate\Support\Facades\Auth;
use App\Models\NguoiDungCaNhan;

class ReminderController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->ID_USER;

        $reminders = Reminder::where('ID_USER', $userId)
            ->orderBy('THOI_GIAN_TRUOC_HAN', 'asc')
            ->get();

        return view('reminders', compact('reminders'));
    }

    //nguyenthaianh
    public function reminders_nguoidungcanhan()
    {
        //$userId = Auth::user()->ID_USER;  // ID_USER của người dùng đã đăng nhập
        //$user = NguoiDungCaNhan::find($userId);

        $userId = Auth::user()->id;  // Dùng auth()->id() để lấy ID của người dùng đã đăng nhập
        $user = NguoiDungCaNhan::find($userId);


        // Lấy tất cả công việc của người dùng
        $tasks = $user->tasks()->with(['subTasks', 'plan'])->get();

        return view('tasks.index', compact('tasks'));
    }
}
