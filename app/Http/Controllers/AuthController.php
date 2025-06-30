<?php

namespace App\Http\Controllers;

use App\Models\NguoiDungCaNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }
    public function doRegister(Request $r)
    {
        $r->validate([
            'ho_ten' => 'required|string|max:100',
            'mat_khau' => 'required|min:8|confirmed',
            'email' => 'required|email|unique:nguoi_dung_ca_nhan,email'
        ]);

        $data = $r->only([
            'ho_ten',
            'email',
            'mat_khau',
            'ngay_sinh',
            'gioi_tinh'
        ]);

        $data['mat_khau'] = bcrypt($data['mat_khau']);

        NguoiDungCaNhan::create($data);

        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    public function showLogin()
    {
        return view('login');
    }
    public function doLogin(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $user = NguoiDungCaNhan::where('email', $r->email)->first();

        $isCorrectPassword = Hash::check($r->mat_khau, $user->mat_khau);

        if (!$user || !$isCorrectPassword) {
            return back()->withInput()->with('error', 'Sai email hoặc mật khẩu!');
        }

        session(['user' => $user]);
        return redirect('/')->with('success', 'Đăng nhập thành công!');
    }

}