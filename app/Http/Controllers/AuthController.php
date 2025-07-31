<?php
namespace App\Http\Controllers;

use App\Models\NguoiDungCaNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



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
            'mat_khau' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'email' => 'required|email|unique:nguoi_dung_ca_nhan,email',
            'ngay_sinh' => 'required|date|before:today',
        ], [
            // Mật khẩu
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'mat_khau.regex' => 'Mật khẩu phải có ít nhất một chữ hoa, một chữ thường và một số.',

            // Email
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',

            // Ngày sinh
            'ngay_sinh.required' => 'Vui lòng nhập ngày sinh.',
            'ngay_sinh.date' => 'Ngày sinh không hợp lệ.',
            'ngay_sinh.before' => 'Ngày sinh phải trước ngày hôm nay.',
        ]);



        $data = [
            'HO_TEN' => $r->ho_ten,
            'EMAIL' => $r->email,
            'NGAY_SINH' => $r->ngay_sinh,
            'GIOI_TINH' => $r->gioi_tinh,
            'MAT_KHAU' => bcrypt($r->mat_khau),
            'AVATAR' => 'avt.jpg',
            'ANH_BIA' => 'anhbia.jpg',
        ];

        NguoiDungCaNhan::create($data);

        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    public function showLogin(){
        return view('login');
    }

    public function doLogin(Request $r)
    {
        $r->validate([
            'email' => 'required|email',
            'mat_khau' => 'required',
        ]);

        $user = NguoiDungCaNhan::where('email', $r->email)->first();

        if (!$user || !Hash::check($r->mat_khau, hashedValue: $user->MAT_KHAU)) {
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
            'mat_khau_moi' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'mat_khau_moi.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'mat_khau_moi.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'mat_khau_moi.regex' => 'Mật khẩu phải có ít nhất một chữ hoa, một chữ thường và một số.',
        ]);


        $user = NguoiDungCaNhan::where('email', $r->email)->first();
        NguoiDungCaNhan::where('email', $r->email)
        ->update(['MAT_KHAU' => Hash::make($r->mat_khau_moi)]);

        session(['user' => $user]);
        return redirect('/login')->with('success', 'Đổi mật khẩu thành công');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        /** @var \App\Models\NguoiDungCaNhan $user */

        $user = Auth::user();
        if (!$user) return redirect('/login');

        $file = $request->file('avatar');
        $fileName = 'avatar' . $user->ID_USER . '.jpg'; // luôn lưu đuôi .jpg

        $imageManager = new ImageManager(new Driver());
        $image = $imageManager->read($file)->toJpeg(85); // 85% chất lượng JPG

        $image->save(public_path('uploads/' . $fileName));

        $user->AVATAR = $fileName;
        $user->save();

        return back()->with('success', 'Đổi avatar thành công!');
    }

    public function uploadAnhBia(Request $request)
    {
        $request->validate([
            'anh_bia' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        /** @var \App\Models\NguoiDungCaNhan $user */
        $user = Auth::user();
        if (!$user) return redirect('/login');

        $file = $request->file('anh_bia');

        // Tạo tên file lưu với đuôi .jpg để ghi đè
        $fileName = 'anhbia' . $user->ID_USER . '.jpg';
        $filePath = public_path('uploads/' . $fileName);

        // Resize và chuyển ảnh sang .jpg dùng Intervention
        $imageManager = new ImageManager(new Driver());
        $image = $imageManager
            ->read($file)
            ->resizeDown(1200, 400)
            ->toJpeg(85);

        // Lưu ảnh đã xử lý
        $image->save($filePath);

        // Cập nhật DB
        $user->ANH_BIA = $fileName;
        $user->save();

        Auth::login($user); // Làm mới Auth

        return back()->with('success', 'Đổi ảnh bìa thành công!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
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
        $updatedUser = NguoiDungCaNhan::where('ID_USER', $user->ID_USER)->first();
        Auth::login($updatedUser);

        // Trả về trang profile với thông báo
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
}



