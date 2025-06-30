<?php

namespace App\Http\Controllers;

use App\Models\NguoiDungCaNhan;
use Illuminate\Http\Request;

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

        return back()->with('success', 'Đăng ký thành công!');
    }
}
