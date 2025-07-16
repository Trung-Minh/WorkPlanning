<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MucCongViec;
use Illuminate\Support\Facades\Auth;

class PlansController extends Controller
{
    public function index()
    {
        $keHoachs = DB::table('KE_HOACH')->get();

        foreach ($keHoachs as $keHoach) {
            $keHoach->cong_viec = DB::table('CONG_VIEC')
                ->where('ID_KH', $keHoach->ID_KH)
                ->get();

            foreach ($keHoach->cong_viec as $cv) {
                $cv->muc_cong_viec = DB::table('MUC_CONG_VIEC')
                    ->where('ID_CV', $cv->ID_CV)
                    ->get();
            }
        }

        return view('plans', compact('keHoachs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TEN_KE_HOACH' => 'required|string|max:255',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập!');
        }

        DB::table('KE_HOACH')->insert([
            'TEN_KE_HOACH' => $request->TEN_KE_HOACH,
            'NGUOI_TAO' => Auth::user()->ID_USER,
        ]);

        return redirect()->route('plans.index');
    }

    public function storeTask(Request $request)
    {
        DB::table('CONG_VIEC')->insert([
            'TEN_CV' => $request->TEN_CV,
            'TIEN_DO' => $request->TIEN_DO,
            'DO_UU_TIEN' => $request->DO_UU_TIEN,
            'ID_KH' => $request->ID_KH,
        ]);

        return redirect()->route('plans.index');
    }

    public function storeSubtask(Request $request)
    {
        DB::table('MUC_CONG_VIEC')->insert([
            'TEN_MUC' => $request->TEN_MUC,
            'ID_CV' => $request->ID_CV,
            'NOI_DUNG_CHI_TIET' => $request->NOI_DUNG_CHI_TIET,
            'THOI_HAN_HOAN_THANH' => $request->THOI_HAN_HOAN_THANH,
            'DO_UU_TIEN_MUC' => $request->DO_UU_TIEN_MUC,
        ]);

        return redirect()->route('plans.index');
    }

    public function deletePlan($id)
    {
        DB::table('MUC_CONG_VIEC')->whereIn('ID_CV', function ($q) use ($id) {
            $q->select('ID_CV')->from('CONG_VIEC')->where('ID_KH', $id);
        })->delete();

        DB::table('CONG_VIEC')->where('ID_KH', $id)->delete();
        DB::table('KE_HOACH')->where('ID_KH', $id)->delete();

        return redirect()->route('plans.index');
    }

    public function destroyTask($id)
    {
        DB::table('MUC_CONG_VIEC')->where('ID_CV', $id)->delete();
        DB::table('CONG_VIEC')->where('ID_CV', $id)->delete();

        return redirect()->route('plans.index');
    }

    public function deleteSubtask($id)
    {
        DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->delete();

        return redirect()->route('plans.index');
    }

    public function renderCongViecHtml($id)
    {
        $congViecs = DB::table('CONG_VIEC')->where('ID_KH', $id)->get();

        foreach ($congViecs as $cv) {
            $cv->muc_cong_viec = DB::table('MUC_CONG_VIEC')
                ->where('ID_CV', $cv->ID_CV)
                ->get();
        }

        return view('partials.cong-viec-list', compact('congViecs'));
    }

    public function updatePlan(Request $request, $id)
    {
        DB::table('KE_HOACH')
            ->where('ID_KH', $id)
            ->update([
                'TEN_KE_HOACH' => $request->TEN_KE_HOACH,
            ]);

        return redirect()->route('plans.index');
    }

    public function updateTask(Request $request, $id)
    {
        $fields = [];

        if ($request->has('TEN_CV')) {
            $fields['TEN_CV'] = $request->TEN_CV;
        }
        if ($request->has('TIEN_DO')) {
            $fields['TIEN_DO'] = $request->TIEN_DO;
        }
        if ($request->has('DO_UU_TIEN')) {
            $fields['DO_UU_TIEN'] = $request->DO_UU_TIEN;
        }

        if (! empty($fields)) {
            DB::table('CONG_VIEC')->where('ID_CV', $id)->update($fields);
        }

        return response()->json(['success' => true]);
    }

    public function updateSubtask(Request $request, $id)
    {
        $fields = [];

        if ($request->has('TEN_MUC')) {
            $fields['TEN_MUC'] = $request->TEN_MUC;
        }
        if ($request->has('NOI_DUNG_CHI_TIET')) {
            $fields['NOI_DUNG_CHI_TIET'] = $request->NOI_DUNG_CHI_TIET;
        }
        if ($request->has('THOI_HAN_HOAN_THANH')) {
            $fields['THOI_HAN_HOAN_THANH'] = $request->THOI_HAN_HOAN_THANH;
        }
        if ($request->has('DO_UU_TIEN_MUC')) {
            $fields['DO_UU_TIEN_MUC'] = $request->DO_UU_TIEN_MUC;
        }

        if (! empty($fields)) {
            DB::table('MUC_CONG_VIEC')
                ->where('ID_MUC', $id)
                ->update($fields);
        }

        return response()->json(['success' => true]);
    }

    public function updateSubtask1(Request $request, $id)
    {
        DB::table('MUC_CONG_VIEC')
            ->where('ID_MUC', $id)
            ->update([
                'TEN_MUC' => $request->TEN_MUC,
                'NOI_DUNG_CHI_TIET' => $request->NOI_DUNG_CHI_TIET,
                'THOI_HAN_HOAN_THANH' => $request->THOI_HAN_HOAN_THANH,
                'DO_UU_TIEN_MUC' => $request->DO_UU_TIEN_MUC,
                'TRANG_THAI' => $request->has('TRANG_THAI') ? 1 : 0,
            ]);

        return redirect()->route('plans.index');
    }

    public function toggleStatus($id)
    {
        $muc = MucCongViec::findOrFail($id);
        $muc->TRANG_THAI = ! $muc->TRANG_THAI;
        $muc->save();

        return response()->json(['success' => true, 'status' => $muc->TRANG_THAI]);
    }
}
