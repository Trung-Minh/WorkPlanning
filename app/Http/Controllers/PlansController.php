<?php

namespace App\Http\Controllers;

use App\Models\MucCongViec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\KeHoach;
use App\Models\CongViec;

use Illuminate\Support\Str;


class PlansController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập!');
        }

        $keHoachs = DB::table('KE_HOACH')
            ->where('NGUOI_TAO', operator: Auth::user()->ID_USER)
            ->whereNull('ID_NHOM')
            ->get();

        foreach ($keHoachs as $keHoach) {
            $keHoach->CONG_VIEC = DB::table('CONG_VIEC')
                ->where('ID_KH', $keHoach->ID_KH)
                ->orderBy('DO_UU_TIEN', 'asc')
                ->get();

            foreach ($keHoach->CONG_VIEC as $cv) {
                $cv->muc_CONG_VIEC = DB::table('MUC_CONG_VIEC')
                    ->where('ID_CV', $cv->ID_CV)
                    ->orderBy('DO_UU_TIEN_MUC', 'asc')
                    ->get();

                $tong = $cv->MUC_CONG_VIEC->count();
                $hoanThanh = $cv->MUC_CONG_VIEC->where('TRANG_THAI', 1)->count();
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

        if (! Auth::check()) {
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
        $request->validate([
            'TEN_MUC' => 'required|string|max:255',
            'ID_CV' => 'required|exists:CONG_VIEC,ID_CV',
            'THOI_HAN_HOAN_THANH' => 'required|date',
            'NOI_DUNG_CHI_TIET' => 'nullable|string',
            'DO_UU_TIEN_MUC' => 'nullable|integer|min:1',
        ]);

        $idCv = $request->ID_CV;
        $requestedPriority = $request->input('DO_UU_TIEN_MUC') ?? 1;

        // Lấy danh sách độ ưu tiên hiện có
        $existingPriorities = DB::table('MUC_CONG_VIEC')
            ->where('ID_CV', $idCv)
            ->orderBy('DO_UU_TIEN_MUC')
            ->pluck('DO_UU_TIEN_MUC')
            ->toArray();

        $maxPriority = count($existingPriorities) > 0 ? max($existingPriorities) : 0;

        if (!in_array($requestedPriority, $existingPriorities)) {
            $newPriority = $requestedPriority > ($maxPriority + 1) ? ($maxPriority + 1) : $requestedPriority;
        } else {
            DB::table('MUC_CONG_VIEC')
                ->where('ID_CV', $idCv)
                ->where('DO_UU_TIEN_MUC', '>=', $requestedPriority)
                ->increment('DO_UU_TIEN_MUC');

            $newPriority = $requestedPriority;
        }

        DB::table('MUC_CONG_VIEC')->insert([
            'TEN_MUC' => $request->TEN_MUC,
            'ID_CV' => $idCv,
            'NOI_DUNG_CHI_TIET' => $request->NOI_DUNG_CHI_TIET,
            'THOI_HAN_HOAN_THANH' => $request->THOI_HAN_HOAN_THANH,
            'DO_UU_TIEN_MUC' => $newPriority,
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

        // 1. Lấy danh sách ID_MUC liên quan đến công việc
        $mucIds = DB::table('MUC_CONG_VIEC')->where('ID_CV', $id)->pluck('ID_MUC');

        // 2. Xoá cấu hình thông báo trước
        DB::table('CAU_HINH_THONG_BAO')->whereIn('ID_MUC', $mucIds)->delete();

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
        // Lấy mục công việc cần xoá
        $muc = DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->first();

        if (!$muc) {
            return redirect()->route('plans.index')->with('error', 'Không tìm thấy mục công việc');
        }

        $idCv = $muc->ID_CV;
        $priority = $muc->DO_UU_TIEN_MUC;

        // Xoá cấu hình thông báo liên quan trước
        DB::table('CAU_HINH_THONG_BAO')->where('ID_MUC', $id)->delete();

        // Xoá mục công việc
        DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->delete();

        // Giảm độ ưu tiên của các mục phía sau
        DB::table('MUC_CONG_VIEC')
            ->where('ID_CV', $idCv)
            ->where('DO_UU_TIEN_MUC', '>', $priority)
            ->decrement('DO_UU_TIEN_MUC');

        return redirect()->route('plans.index')->with('success', 'Đã xoá mục công việc và cập nhật lại độ ưu tiên.');
    }

    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'TEN_KE_HOACH' => 'required|string|max:255',
        ]);

        $keHoach = DB::table('KE_HOACH')->where('ID_KH', $id)->first();

        if (!$keHoach) {
            return response()->json(['success' => false, 'message' => 'Kế hoạch không tồn tại.'], 404);
        }

        DB::table('KE_HOACH')
            ->where('ID_KH', $id)
            ->update([
                'TEN_KE_HOACH' => $request->TEN_KE_HOACH,
            ]);

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
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

    public function deletePlan($id)
    {
        $plan = KeHoach::find($id);

        if (!$plan) {
            return redirect()->back()->with('error', 'Không tìm thấy kế hoạch!');
        }

        // Tìm tất cả công việc trong kế hoạch
        $tasks = CongViec::where('ID_KH', $id)->get();

        foreach ($tasks as $task) {
            MucCongViec::where('ID_CV', $task->ID_CV)->delete();
        }

        // Xoá toàn bộ công việc thuộc kế hoạch
        CongViec::where('ID_KH', $id)->delete();

        $plan->delete();

        return redirect()->back()->with('success', 'Đã xoá kế hoạch và toàn bộ dữ liệu liên quan!');
    }

    public function updateSubtask1(Request $request, $id)
    {
        $muc = DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->first();

        if (!$muc) {
            return response()->json(['error' => 'Không tìm thấy mục công việc'], 404);
        }

        $idCv = $muc->ID_CV;
        $oldPriority = $muc->DO_UU_TIEN_MUC;
        $inputPriority = $request->input('DO_UU_TIEN_MUC', $oldPriority);

        // Giới hạn độ ưu tiên không vượt quá số mục hiện có
        $count = DB::table('MUC_CONG_VIEC')->where('ID_CV', $idCv)->count();
        $newPriority = min(max(1, $inputPriority), $count);

        // Cập nhật lại độ ưu tiên nếu thay đổi
        if ($newPriority != $oldPriority) {
            if ($newPriority > $oldPriority) {
                DB::table('MUC_CONG_VIEC')
                    ->where('ID_CV', $idCv)
                    ->whereBetween('DO_UU_TIEN_MUC', [$oldPriority + 1, $newPriority])
                    ->decrement('DO_UU_TIEN_MUC');
            } else {
                DB::table('MUC_CONG_VIEC')
                    ->where('ID_CV', $idCv)
                    ->whereBetween('DO_UU_TIEN_MUC', [$newPriority, $oldPriority - 1])
                    ->increment('DO_UU_TIEN_MUC');
            }
        }

        // Chỉ update các trường được gửi lên
        $updateData = [];
        if ($request->has('TEN_MUC')) $updateData['TEN_MUC'] = $request->TEN_MUC;
        if ($request->has('NOI_DUNG_CHI_TIET')) $updateData['NOI_DUNG_CHI_TIET'] = $request->NOI_DUNG_CHI_TIET;
        if ($request->has('THOI_HAN_HOAN_THANH')) $updateData['THOI_HAN_HOAN_THANH'] = $request->THOI_HAN_HOAN_THANH;
        if ($request->has('DO_UU_TIEN_MUC')) $updateData['DO_UU_TIEN_MUC'] = $newPriority;
        if ($request->has('TRANG_THAI')) $updateData['TRANG_THAI'] = $request->TRANG_THAI ? 1 : 0;

        DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->update($updateData);

        return redirect()->back();
    }



    public function toggleStatus($id)
    {
        $muc = MucCongViec::findOrFail($id);
        $muc->TRANG_THAI = ! $muc->TRANG_THAI;
        $muc->save();

        return response()->json(['success' => true, 'status' => $muc->TRANG_THAI]);
    }

    public function updateTaskPriority(Request $request, $id)
    {
        $inputPriority = (int) $request->DO_UU_TIEN;

        // 1. Lấy công việc hiện tại
        $task = DB::table('CONG_VIEC')->where('ID_CV', $id)->first();
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy công việc.']);
        }

        $idKh = $task->ID_KH;
        $oldPriority = $task->DO_UU_TIEN;

        // 2. Lấy max độ ưu tiên hiện có trong kế hoạch
        $maxPriority = DB::table('CONG_VIEC')
                        ->where('ID_KH', $idKh)
                        ->count();

        // 3. Giới hạn inputPriority
        if ($inputPriority < 1) {
            $newPriority = 1;
        } elseif ($inputPriority > $maxPriority) {
            $newPriority = $maxPriority ;
        } else {
            $newPriority = $inputPriority;
        }

        // 4. Nếu không thay đổi thì thôi
        if ($newPriority == $oldPriority) {
            return response()->json(['success' => true, 'message' => 'Không có thay đổi']);
        }

        // 5. Xử lý cập nhật ưu tiên các task khác
        if ($newPriority < $oldPriority) {
            // Từ 5 → 2 → các task [2..4] +1
            DB::table('CONG_VIEC')
                ->where('ID_KH', $idKh)
                ->whereBetween('DO_UU_TIEN', [$newPriority, $oldPriority - 1])
                ->increment('DO_UU_TIEN');
        } elseif ($newPriority > $oldPriority) {
            // Từ 3 → 6 → các task [4..6] -1
            DB::table('CONG_VIEC')
                ->where('ID_KH', $idKh)
                ->whereBetween('DO_UU_TIEN', [$oldPriority + 1, $newPriority])
                ->decrement('DO_UU_TIEN');
        }

        // 6. Cập nhật lại công việc hiện tại
        DB::table('CONG_VIEC')
            ->where('ID_CV', $id)
            ->update(['DO_UU_TIEN' => $newPriority]);

        return response()->json(['success' => true, 'new' => $newPriority]);
    }

    public function updateSubtaskPriority(Request $request, $id)
    {
        $inputPriority = (int) $request->DO_UU_TIEN_MUC;

        // 1. Lấy mục công việc hiện tại
        $subtask = DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->first();
        if (!$subtask) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy mục công việc.']);
        }

        $idCv = $subtask->ID_CV;
        $oldPriority = $subtask->DO_UU_TIEN_MUC;

        // 2. Lấy số lượng mục trong cùng công việc
        $maxPriority = DB::table('MUC_CONG_VIEC')
                        ->where('ID_CV', $idCv)
                        ->count();

        // 3. Giới hạn inputPriority
        if ($inputPriority < 1) {
            $newPriority = 1;
        } elseif ($inputPriority > $maxPriority) {
            $newPriority = $maxPriority ;
        } else {
            $newPriority = $inputPriority;
        }

        // 4. Không thay đổi thì bỏ qua
        if ($newPriority == $oldPriority) {
            return response()->json(['success' => true, 'message' => 'Không có thay đổi.']);
        }

        // 5. Điều chỉnh các mục khác
        if ($newPriority < $oldPriority) {
            DB::table('MUC_CONG_VIEC')
                ->where('ID_CV', $idCv)
                ->whereBetween('DO_UU_TIEN_MUC', [$newPriority, $oldPriority - 1])
                ->increment('DO_UU_TIEN_MUC');
        } elseif ($newPriority > $oldPriority) {
            DB::table('MUC_CONG_VIEC')
                ->where('ID_CV', $idCv)
                ->whereBetween('DO_UU_TIEN_MUC', [$oldPriority + 1, $newPriority])
                ->decrement('DO_UU_TIEN_MUC');
        }

        // 6. Cập nhật mục hiện tại
        DB::table('MUC_CONG_VIEC')
            ->where('ID_MUC', $id)
            ->update(['DO_UU_TIEN_MUC' => $newPriority]);

        return response()->json(['success' => true, 'new' => $newPriority]);
    }
}
