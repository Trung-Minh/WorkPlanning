<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MucCongViec;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;


class PlansController extends Controller
{
    public function index()
    {
        $keHoachs = DB::table('KE_HOACH')->get();

        foreach ($keHoachs as $keHoach) {
            $keHoach->cong_viec = DB::table('CONG_VIEC')
                ->where('ID_KH', $keHoach->ID_KH)
                ->orderBy('DO_UU_TIEN', 'asc')
                ->get();

            foreach ($keHoach->cong_viec as $cv) {
                $cv->muc_cong_viec = DB::table('MUC_CONG_VIEC')
                    ->where('ID_CV', $cv->ID_CV)
                    ->get();

                $tong = $cv->muc_cong_viec->count();
                $hoanThanh = $cv->muc_cong_viec->where('TRANG_THAI', 1)->count();
                $tienDo = $tong > 0 ? round($hoanThanh / $tong * 100) : 0;

    
                $cv->TIEN_DO = $tienDo;

                DB::table('CONG_VIEC')
                    ->where('ID_CV', $cv->ID_CV)
                    ->update(['TIEN_DO' => $tienDo]);
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
        // ✅ Validate dữ liệu
        $request->validate([
            'TEN_CV' => 'required|string|max:255',
            'TIEN_DO' => 'nullable|numeric|min:0|max:100',
            'DO_UU_TIEN' => 'nullable|integer|min:1',
            'ID_KH' => 'required|string|exists:KE_HOACH,ID_KH',
        ]);

        // ✅ Tạo ID công việc
        $idCv = 'CV' . strtoupper(Str::random(6));
        $idKh = $request->input('ID_KH');
        $requestedPriority = $request->input('DO_UU_TIEN') ?? 1;

        // ✅ Lấy danh sách độ ưu tiên hiện có
        $existingPriorities = DB::table('CONG_VIEC')
            ->where('ID_KH', $idKh)
            ->orderBy('DO_UU_TIEN')
            ->pluck('DO_UU_TIEN')
            ->toArray();

        $maxPriority = count($existingPriorities) > 0 ? max($existingPriorities) : 0;

        if (!in_array($requestedPriority, $existingPriorities)) {
            // Nếu nhập lớn hơn max → đưa về max + 1
            $newPriority = $requestedPriority > ($maxPriority + 1) ? ($maxPriority + 1) : $requestedPriority;
        } else {
            // Nếu đã tồn tại → đẩy các công việc phía sau lên 1
            DB::table('CONG_VIEC')
                ->where('ID_KH', $idKh)
                ->where('DO_UU_TIEN', '>=', $requestedPriority)
                ->increment('DO_UU_TIEN');

            $newPriority = $requestedPriority;
        }

        // ✅ Thêm công việc mới
        DB::table('CONG_VIEC')->insert([
            'ID_CV' => $idCv,
            'TEN_CV' => $request->input('TEN_CV'),
            'TIEN_DO' => $request->input('TIEN_DO') ?? 0,
            'DO_UU_TIEN' => $newPriority,
            'ID_KH' => $idKh,
        ]);

        return redirect()->route('plans.index')->with('success', 'Đã thêm công việc thành công!');
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




        
    public function destroyTask($id)
    {
        // Lấy thông tin công việc cần xoá
        $task = DB::table('CONG_VIEC')->where('ID_CV', $id)->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Không tìm thấy công việc');
        }

        $idKh = $task->ID_KH;
        $priority = $task->DO_UU_TIEN;

        // Xoá tất cả các mục công việc liên quan
        DB::table('MUC_CONG_VIEC')->where('ID_CV', $id)->delete();

        // Xoá công việc
        DB::table('CONG_VIEC')->where('ID_CV', $id)->delete();

        // Cập nhật lại độ ưu tiên: giảm các công việc sau đó
        DB::table('CONG_VIEC')
            ->where('ID_KH', $idKh)
            ->where('DO_UU_TIEN', '>', $priority)
            ->decrement('DO_UU_TIEN');

        return redirect()->route('plans.index')->with('success', 'Đã xóa công việc và cập nhật lại độ ưu tiên.');
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


    // PlansController
    // public function reorder(Request $request) {
    //     foreach ($request->tasks as $task) {
    //         DB::table('CONG_VIEC')->where('ID_CV', $task['id'])->update(['DO_UU_TIEN' => $task['priority']]);
    //     }
    //     return response()->json(['success' => true]);
    // }

   
}
