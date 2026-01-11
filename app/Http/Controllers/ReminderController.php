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

        // 1. Kế hoạch cá nhân
        $keHoachCaNhan = KeHoach::with('congViecs.mucCongViecs')
            ->where('NGUOI_TAO', $userId)
            ->whereNull('ID_NHOM')
            ->get();

        // 2. Kế hoạch nhóm
        $keHoachNhom = KeHoach::with('congViecs.mucCongViecs')
            ->where('NGUOI_TAO', $userId)
            ->whereNotNull('ID_NHOM')
            ->get();

        $thongBaos = CauHinhThongBao::with('mucCongViec')
            ->where('ID_USER', $userId)
            ->orderBy('THOI_DIEM_THONG_BAO', 'asc')
            ->get();

        $reminderData = $thongBaos->map(function ($tb) {
            return [
                'id' => $tb->ID_CAUHINH,
                'noi_dung' => $tb->mucCongViec->TEN_MUC ?? 'Không xác định',
                'thoidiem_thongbao' => $tb->THOI_DIEM_THONG_BAO,
                'thoihan_hoanthanh' => optional($tb->mucCongViec->THOI_HAN_HOAN_THANH)?->toDateTimeString(),
            ];
        });

        return view('reminders', [
            'keHoachCaNhan' => $keHoachCaNhan,
            'keHoachNhom' => $keHoachNhom,
            'thongBaos' => $thongBaos,
            'reminderData' => $reminderData
        ]);
    }

    public function set(Request $request)
    {
        $request->validate([
            'id_muc' => 'required|exists:MUC_CONG_VIEC,ID_MUC',
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

    public function getDeadlineByCauHinh($id)
    {
        $cauhinh = CauHinhThongBao::with('mucCongViec')->findOrFail($id);
        $deadline = optional($cauhinh->mucCongViec)->THOI_HAN_HOAN_THANH;

        return response()->json([
            'deadline' => $deadline ? $deadline->format('Y-m-d\TH:i') : null
        ]);
    }
}
