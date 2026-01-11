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
                $rawNotifications = DB::table('MUC_CONG_VIEC')
                    ->join('CONG_VIEC', 'MUC_CONG_VIEC.ID_CV', '=', 'CONG_VIEC.ID_CV')
                    ->join('KE_HOACH', 'CONG_VIEC.ID_KH', '=', 'KE_HOACH.ID_KH')
                    ->where('KE_HOACH.NGUOI_TAO', Auth::user()->ID_USER)
                    ->where('MUC_CONG_VIEC.TRANG_THAI', 0)
                    ->whereBetween('THOI_HAN_HOAN_THANH', [now(), now()->addDays(3)])
                    ->select('CONG_VIEC.TEN_CV', 'MUC_CONG_VIEC.TEN_MUC', 'MUC_CONG_VIEC.THOI_HAN_HOAN_THANH')
                    ->orderBy('THOI_HAN_HOAN_THANH')
                    ->get();

                // Nhóm theo TEN_CV
                $notifications = $rawNotifications->groupBy('TEN_CV');

                // Thông báo lời mời nhóm
                $userId = Auth::user()->ID_USER;

                $invitations = DB::table('LOI_MOI')
                    ->join('NHOM_LAM_VIEC', 'LOI_MOI.ID_NHOM', '=', 'NHOM_LAM_VIEC.ID_NHOM')
                    ->join('NGUOI_DUNG_CA_NHAN', 'NHOM_LAM_VIEC.ID_NHOM_TRUONG', '=', 'NGUOI_DUNG_CA_NHAN.ID_USER')
                    ->where('LOI_MOI.ID_USER', $userId)
                    ->whereNull('LOI_MOI.TRANG_THAI_LOI_MOI')
                    ->select('NHOM_LAM_VIEC.TEN_NHOM', 'LOI_MOI.ID_NHOM', 'NGUOI_DUNG_CA_NHAN.HO_TEN as NGUOI_MOI')
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
