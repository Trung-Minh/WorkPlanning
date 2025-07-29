<?php

namespace App\Providers;

use App\Models\CauHinhThongBao;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $notifications = [];
            $invitations = [];

            if (Auth::check()) {
                $rawNotifications = DB::table('muc_cong_viec')
                    ->join('cong_viec', 'muc_cong_viec.ID_CV', '=', 'cong_viec.ID_CV')
                    ->join('ke_hoach', 'cong_viec.ID_KH', '=', 'ke_hoach.ID_KH')
                    ->where('ke_hoach.NGUOI_TAO', Auth::user()->ID_USER)
                    ->where('muc_cong_viec.TRANG_THAI', 0)
                    ->whereBetween('THOI_HAN_HOAN_THANH', [now(), now()->addDays(3)])
                    ->select('cong_viec.TEN_CV', 'muc_cong_viec.TEN_MUC', 'muc_cong_viec.THOI_HAN_HOAN_THANH')
                    ->orderBy('THOI_HAN_HOAN_THANH')
                    ->get();

                // Nhóm theo TEN_CV
                $notifications = $rawNotifications->groupBy('TEN_CV');

                // Thông báo lời mời nhóm
                $userId = Auth::user()->ID_USER;

                $invitations = DB::table('loi_moi')
                    ->join('nhom_lam_viec', 'loi_moi.ID_NHOM', '=', 'nhom_lam_viec.ID_NHOM')
                    ->join('nguoi_dung_ca_nhan', 'nhom_lam_viec.ID_NHOM_TRUONG', '=', 'nguoi_dung_ca_nhan.ID_USER')
                    ->where('loi_moi.ID_USER', $userId)
                    ->whereNull('loi_moi.TRANG_THAI_LOI_MOI')
                    ->select('nhom_lam_viec.TEN_NHOM', 'loi_moi.ID_NHOM', 'nguoi_dung_ca_nhan.HO_TEN as NGUOI_MOI')
                    ->get();

                $invitations = $invitations ?? collect();

                $view->with('invitations', $invitations);
            }

            $view->with('notifications', $notifications);

            $js_reminders = CauHinhThongBao::with('mucCongViec')
                ->whereDate('THOI_DIEM_THONG_BAO', today()) // Có thể điều chỉnh điều kiện này
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->ID_CAUHINH,
                        'noi_dung' => $r->mucCongViec->TEN_MUC ?? 'Không xác định',
                        'thoidiem_thongbao' => $r->THOI_DIEM_THONG_BAO,
                        'thoihan_hoanthanh' => optional($r->mucCongViec)->THOI_HAN_HOAN_THANH,
                    ];
                });

            $view->with('js_reminders', $js_reminders);
        });
    }
}
