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
