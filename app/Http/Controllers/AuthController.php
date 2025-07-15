<?php
namespace App\Http\Controllers;

use App\Models\NguoiDungCaNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


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
            'email' => 'required|email|unique:nguoi_dung_ca_nhan,email',
            'ngay_sinh' => 'required|date|before:today',
        ]);

        $data = $r->only([
            'ho_ten',
            'email',
            'mat_khau',
            'ngay_sinh',
            'gioi_tinh',
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

        if (!$user || !Hash::check($r->mat_khau, $user->mat_khau)) {
            return back()->withInput()->with('error', 'Sai email hoặc mật khẩu!');
        }

        Auth::login($user);

        // Chuyển về trang ban đầu bị chặn (nếu có), hoặc trang kế hoạch
        return redirect()->route('welcome')
                            ->with('success', 'Đăng nhập thành công!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success','Đã đăng xuất');
    }

    public function showRepassword()
    {
        return view('repassword');
    }

    public function doRepassword(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
            'mat_khau_moi' => 'required',
            'cm_mat_khau_moi' => 'required',
        ]);

        $user = NguoiDungCaNhan::where('email', $r->email)->first();
        NguoiDungCaNhan::where('email', $r->email)
        ->update(['MAT_KHAU' => Hash::make($r->mat_khau_moi)]);

        session(['user' => $user]);
        return redirect('/')->with('success', 'Đổi mật khẩu thành công');
    }
    public function uploadAvatar(Request $request)
    {
            $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $userSession = session('user');
        $user = NguoiDungCaNhan::find($userSession->ID_USER);
        if (!$user) return redirect('/login');

        $file = $request->file('avatar');
        $fileName = 'avatar' . $user->ID_USER . '.' . $file->getClientOriginalExtension();

        // Lưu file
        $file->move(public_path('uploads'), $fileName);

        // Cập nhật
        $user->AVATAR = $fileName;
        $user->save();
        NguoiDungCaNhan::where('email', $user->EMAIL)
        ->update(['AVATAR' => $fileName]);

        // Cập nhật session
        session(['user' => $user]);

        return back()->with('success', 'Đổi ảnh đại diện thành công!');
    }
    public function uploadAnhBia(Request $request)
    {
        $request->validate([
            'anh_bia' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $userSession = session('user');
        $user = NguoiDungCaNhan::find($userSession->ID_USER);

        if (!$user) return redirect('/login');

        $file = $request->file('anh_bia');
        $fileName = 'anhbia' . $user->ID_USER . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $fileName);

        $user->ANH_BIA = $fileName;
        $user->save();
        NguoiDungCaNhan::where('email', $user->EMAIL)
        ->update(['ANH_BIA' => $fileName]);

        session(key: ['user' => $user]);
        return back()->with('success', 'Đổi ảnh bìa thành công!');
    }
    public function update(Request $request)
    {
        // Lấy user hiện tại từ session
        $user = session('user');

        // Kiểm tra nếu chưa đăng nhập
        if (!$user) {
            return redirect('/login')->with('error', 'Bạn cần đăng nhập.');
        }

        // Validate dữ liệu nhập vào
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'email' => 'required|email|max:255',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:Nam,Nữ,Khác',
        ]);

        // Cập nhật vào CSDL
        NguoiDungCaNhan::where('email', $user->EMAIL)
            ->update([
                'HO_TEN' => $validated['ho_ten'],
                'MO_TA' => $validated['mo_ta'],
                'EMAIL' => $validated['email'],
                'NGAY_SINH' => $validated['ngay_sinh'],
                'GIOI_TINH' => $validated['gioi_tinh'],
            ]);

        // Cập nhật session (nếu cần thiết)
        // Lấy lại user mới từ DB (theo email mới)
        $updatedUser = NguoiDungCaNhan::where('EMAIL', $validated['email'])->first();
        session(['user' => $updatedUser]);

        // Trả về trang profile với thông báo
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}



