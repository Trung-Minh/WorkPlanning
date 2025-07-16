<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder; // Nếu model tên Reminder
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->ID_USER;

        $reminders = Reminder::where('ID_USER', $userId)
            ->orderBy('THOI_GIAN_TRUOC_HAN', 'asc')
            ->get();

        return view('reminders.index', compact('reminders'));
    }
}
