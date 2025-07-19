<?php

namespace App\Http\Controllers;

use App\Models\KeHoach;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; //dùng để nhận dữ liệu từ form
use Illuminate\Support\Facades\DB;
use App\Models\CauHinhThongBao;

class ReminderController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->ID_USER;

        $reminders = KeHoach::with('congViecs.mucCongViecs')
            ->where('NGUOI_TAO', $userId)
            ->get();

        $thongBaos = CauHinhThongBao::with('mucCongViec')
            ->where('ID_USER', $userId)
            ->orderBy('THOI_DIEM_THONG_BAO', 'asc')
            ->get();

        return view('reminders', compact('reminders', 'thongBaos'));
    }

    public function set(Request $request)
    {
        $request->validate([
            'id_muc' => 'required|exists:muc_cong_viec,ID_MUC',
            'thoi_gian' => 'required|date',
        ]);

        // Lưu vào bảng cấu hình hoặc bảng riêng
        $hienthi_ttnhacnho = CauHinhThongBao::with('mucCongViec')->create([
            'ID_USER' => Auth::user()->ID_USER,
            'ID_MUC' => $request->id_muc,
            'THOI_DIEM_THONG_BAO' => $request->thoi_gian,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', $hienthi_ttnhacnho);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'thoi_gian' => 'required|date',
        ]);

        $thongBao = CauHinhThongBao::findOrFail($id);
        $thongBao->THOI_DIEM_THONG_BAO = $request->thoi_gian;
        $thongBao->save();

        return redirect()->back()->with('success_update', 'Cập nhật nhắc nhở thành công.');
    }

    public function delete($id)
    {
        $thongBao = CauHinhThongBao::findOrFail($id);
        $thongBao->delete();

        return redirect()->back()->with('success_delete', 'Đã xoá nhắc nhở.');
    }
}
