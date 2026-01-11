<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDungCaNhan;
use App\Models\Nhom;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\MucCongViec;
use App\Models\KeHoach;
use App\Models\CongViec;


class LeaderController extends Controller
{
    public function showLeader(){
        return view('leader');
    }

    public function search_members(Request $r)
    {
        $r->validate([
            'search_members' => 'required',
            'ten_nhom' => 'required',
            'id_nhom' => 'required|exists:NHOM_LAM_VIEC,ID_NHOM',
            'id_nhom_truong' => 'required|exists:NHOM_LAM_VIEC,ID_NHOM_TRUONG',

        ]);

        $user = NguoiDungCaNhan::where('HO_TEN','like',"%{$r->input('search_members')}%")
                                ->where('ID_USER', '!=', $r->input('id_nhom_truong') )
                                ->get();

        Nhom::where('ID_NHOM', $r->input('id_nhom'))->update(['TEN_NHOM' => $r->ten_nhom]);
        $nhom = Nhom::where('ID_NHOM', $r->input('id_nhom'))->first();

        session(['nhom' => $nhom]);
        session()->push('invited_success', $r->user_id);

        return redirect()->back()
            ->withInput()
            ->with('invite', value: $user);
    }

    public function addgroup (Request $r)
    {
        $r->validate([
            'id_user' => 'required',
        ]);
        $ngayHomNay = Carbon::now();
        $data = [
            'ID_NHOM_TRUONG' => $r->input('id_user'),
            'NGAY_TAO' => $ngayHomNay,
            'TEN_NHOM' => 'CHƯA CÓ TÊN',
        ];

        Nhom::create($data);
        $nhom = Nhom::where('ID_NHOM_TRUONG', $r->input('id_user'))
                                ->where('NGAY_TAO', $ngayHomNay)
                                ->where('TEN_NHOM', 'CHƯA CÓ TÊN')->first();

        session(['nhom' => $nhom]);

        return redirect()->route('showLeader') ;
    }

    public function invite (Request $request){

        $request->validate([
            'id_user' => 'required|exists:NGUOI_DUNG_CA_NHAN,ID_USER',
            'id_nhom' => 'required|exists:NHOM_LAM_VIEC,ID_NHOM'
        ]);

        DB::table('LOI_MOI')->insert([
            'ID_USER' => $request->input('id_user'),
            'ID_NHOM' =>  $request->input('id_nhom'),
        ]);
        return redirect()->back()->with('invite_success', '✅ Đã gửi lời mời thành công!');;
    }

    public function doGroups(Request $request){

        $request->validate([
            'ten_nhom' => 'required',
            'id_nhom' => 'required|exists:NHOM_LAM_VIEC,ID_NHOM'
        ]);

        Nhom::where('ID_NHOM', $request->input('id_nhom'))->update(['TEN_NHOM' => $request->ten_nhom]);
        $nhom = Nhom::where('ID_NHOM', $request->input('id_nhom'))->first();

        session(['group' => $nhom]);
        return redirect()->route('showGroup') ;
    }

    public function delete_group(Request $request){

        $request->validate([
            'id_nhom' => 'required|exists:NHOM_LAM_VIEC,ID_NHOM',
            'redirect_to' => 'required'
        ]);

        DB::table('LOI_MOI')
            ->where('ID_NHOM', $request->input('id_nhom'))
            ->delete();

        Nhom::where('ID_NHOM', $request->input('id_nhom'))->delete();

        return redirect()->to($request->input('redirect_to'));
    }

    public function chapNhan($id)
    {
        $userId = Auth::id();

        // 1. Cập nhật lời mời
        DB::table('LOI_MOI')
            ->where('ID_NHOM', $id)
            ->where('ID_USER', $userId)
            ->update(['TRANG_THAI_LOI_MOI' => true]);

        // 2. Thêm vào bảng thành viên nếu chưa tồn tại
        $exists = DB::table('NHOM_THANH_VIEN')
            ->where('ID_NHOM', $id)
            ->where('ID_USER', $userId)
            ->exists();

        if (!$exists) {
            DB::table('NHOM_THANH_VIEN')->insert([
                'ID_NHOM' => $id,
                'ID_USER' => $userId,
            ]);
        }
        return back()->with('success', 'Đã chấp nhận lời mời vào nhóm!');
    }

    public function tuChoi($id)
    {
        DB::table('LOI_MOI')
            ->where('ID_NHOM', $id)
            ->where('ID_USER', Auth::id())
            ->delete();

        return back()->with('info', 'Bạn đã từ chối lời mời.');
    }


    // Hiển thị nhóm hiện tại
    public function showGroup()
    {
        $nhom = session('group');
        $nhom = Nhom::with('truongNhom')->find($nhom->ID_NHOM);

        if (!$nhom) {
            return redirect()->back()->with('error', 'Không có nhóm nào được chọn');
        }

        // Lấy danh sách thành viên
        $thanhVien = DB::table('NHOM_THANH_VIEN')
            ->join('NGUOI_DUNG_CA_NHAN', 'NHOM_THANH_VIEN.ID_USER', '=', 'NGUOI_DUNG_CA_NHAN.ID_USER')
            ->where('ID_NHOM', $nhom->ID_NHOM)
            ->get();

        // Lấy danh sách kế hoạch theo nhóm
        $keHoachs = DB::table('KE_HOACH')
            ->where('ID_NHOM', $nhom->ID_NHOM)
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

                // Tính tiến độ
                $tong = $cv->MUC_CONG_VIEC->count();
                $hoanThanh = $cv->MUC_CONG_VIEC->where('TRANG_THAI', 1)->count();
                $tienDo = $tong > 0 ? round($hoanThanh / $tong * 100) : 0;

                $cv->TIEN_DO = $tienDo;

                // Cập nhật tiến độ vào DB
                DB::table('CONG_VIEC')
                    ->where('ID_CV', $cv->ID_CV)
                    ->update(['TIEN_DO' => $tienDo]);
            }
        }

        return view('group', compact('nhom', 'thanhVien', 'keHoachs'));
    }



    public function doGroup(Request $request)
    {
        $request->validate([
            'id_nhom' => 'required|exists:NHOM_LAM_VIEC,ID_NHOM'
        ]);

        $nhom = DB::table('NHOM_LAM_VIEC')->where('ID_NHOM', $request->id_nhom)->first();

        session(['group' => $nhom]);

        return redirect()->route('showGroup');
    }

    public function updateGroup(Request $request, $id)
    {
        $request->validate([
            'TEN_NHOM' => 'required|string|max:100',
            'MO_TA_NHOM' => 'nullable|string',
            'AVATAR_NHOM' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $nhom = Nhom::findOrFail($id);

        // Check quyền nhóm trưởng
        if ($nhom->ID_NHOM_TRUONG != Auth::user()->ID_USER) {
            abort(403, 'Bạn không có quyền chỉnh sửa nhóm này.');
        }

        $dataUpdate = [
            'TEN_NHOM' => $request->TEN_NHOM,
            'MO_TA_NHOM' => $request->MO_TA_NHOM,
        ];

        // Nếu có file avatar
        if ($request->hasFile('AVATAR_NHOM')) {
            $file = $request->file('AVATAR_NHOM');
            $extension = $file->getClientOriginalExtension();
            $filename = $nhom->ID_NHOM . '.' . $extension;

            $destinationPath = public_path('upload_group');
            $relativePath = 'upload_group/' . $filename;

            // Xóa ảnh cũ nếu có
            if ($nhom->AVATAR_NHOM && file_exists(public_path($nhom->AVATAR_NHOM))) {
                unlink(public_path($nhom->AVATAR_NHOM));
            }

            // Lưu file mới
            $file->move($destinationPath, $filename);

            // Gán đường dẫn vào mảng update
            $dataUpdate['AVATAR_NHOM'] = $relativePath;
        }

        $nhom->update($dataUpdate); // ← chỉ cần gọi 1 lần, gọn gàng 🧼
        session(['group' => $nhom->fresh()]); // Làm mới lại session để lấy giá trị mới

        return redirect()->route('showGroup')->with('success', 'Cập nhật nhóm thành công!');
    }


    public function delete($id)
    {
        $nhom = Nhom::findOrFail($id);

        // Chỉ nhóm trưởng mới được xoá
        if (Auth::user()->ID_USER !== $nhom->ID_NHOM_TRUONG) {
            abort(403, 'Bạn không có quyền xoá nhóm này!');
        }

        DB::transaction(function () use ($id) {
            // 1. Lấy tất cả ID_KH thuộc nhóm
            $keHoachIds = DB::table('KE_HOACH')->where('ID_NHOM', $id)->pluck('ID_KH');

            // 2. Lấy tất cả ID_CV theo các kế hoạch
            $congViecIds = DB::table('CONG_VIEC')->whereIn('ID_KH', $keHoachIds)->pluck('ID_CV');

             // 3. Lấy tất cả ID_MUC theo ID_CV
            $mucIds = DB::table('MUC_CONG_VIEC')->whereIn('ID_CV', $congViecIds)->pluck('ID_MUC');

            // 4. Xoá CAU_HINH_THONG_BAO theo ID_MUC
            DB::table('CAU_HINH_THONG_BAO')->whereIn('ID_MUC', $mucIds)->delete();

            // 5. Xoá MUC_CONG_VIEC theo ID_CV
            DB::table('MUC_CONG_VIEC')->whereIn('ID_CV', $congViecIds)->delete();

            // 6. Xoá CONG_VIEC theo ID_KH
            DB::table('CONG_VIEC')->whereIn('ID_KH', $keHoachIds)->delete();

            // 7. Xoá KE_HOACH theo ID_NHOM
            DB::table('KE_HOACH')->where('ID_NHOM', $id)->delete();

            // 8. Xoá các bảng liên quan đến nhóm
            DB::table('LOI_MOI')->where('ID_NHOM', $id)->delete();
            DB::table('NHOM_THANH_VIEN')->where('ID_NHOM', $id)->delete();

            // 9. Xoá nhóm
            DB::table('NHOM_LAM_VIEC')->where('ID_NHOM', $id)->delete();
        });

        session()->forget('group');

        return redirect()->route(route: 'welcome')->with('success', 'Đã xoá nhóm và toàn bộ dữ liệu liên quan!');
    }


    public function storeGroupPlan(Request $request, $idNhom)
    {
        $request->validate([
            'TEN_KE_HOACH' => 'required|string|max:255',
        ]);

        DB::table('KE_HOACH')->insert([
            'TEN_KE_HOACH' => $request->TEN_KE_HOACH,
            'NGUOI_TAO' => Auth::id(),
            'ID_NHOM' => $idNhom,
        ]);

        return redirect()->route('showGroup', $idNhom);
    }

    public function storeGroupTask(Request $request)
    {
        // ✅ Validate dữ liệu
        $request->validate([
            'TEN_CV' => 'required|string|max:255',
            'TIEN_DO' => 'nullable|numeric|min:0|max:100',
            'DO_UU_TIEN' => 'nullable|integer|min:1',
            'ID_KH' => 'required|string|exists:KE_HOACH,ID_KH',
        ]);

        // ✅ Tạo ID công việc
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
            'TEN_CV' => $request->input('TEN_CV'),
            'TIEN_DO' => $request->input('TIEN_DO') ?? 0,
            'DO_UU_TIEN' => $newPriority,
            'ID_KH' => $idKh,
        ]);

        return redirect()->route('showGroup')->with('success', 'Đã thêm công việc thành công!');
    }

    public function storeGroupSubtask(Request $request)
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

        return redirect()->route('showGroup')->with('success', 'Đã thêm mục công việc thành công!');
    }

    public function updateGroupPlan(Request $request, $id)
    {
        $request->validate([
            'TEN_KE_HOACH' => 'required|string|max:255',
        ]);

        $keHoach = DB::table('KE_HOACH')->where('ID_KH', $id)->first();

        if (!$keHoach) {
            return redirect()->back()->with('error', 'Kế hoạch không tồn tại.');
        }

        DB::table('KE_HOACH')
            ->where('ID_KH', $id)
            ->update([
                'TEN_KE_HOACH' => $request->TEN_KE_HOACH,
            ]);

        return redirect()->route('showGroup')->with('success', 'Cập nhật kế hoạch thành công!');
    }


    public function updateGroupTask(Request $request, $id)
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

    public function updateGroupSubtask(Request $request, $id)
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


    public function deleteGroupPlan($id)
    {
        $plan = KeHoach::find($id);

        if (!$plan) {
            return redirect()->back()->with('error', 'Không tìm thấy kế hoạch!');
        }

        DB::transaction(function () use ($id, $plan) {
            // Tìm tất cả công việc trong kế hoạch
            $tasks = CongViec::where('ID_KH', $id)->get();

            foreach ($tasks as $task) {
                // Tìm tất cả mục công việc của công việc đó
                $subtasks = MucCongViec::where('ID_CV', $task->ID_CV)->get();

                foreach ($subtasks as $subtask) {
                    // Xoá thông báo liên quan đến từng mục công việc
                    DB::table('CAU_HINH_THONG_BAO')->where('ID_MUC', $subtask->ID_MUC)->delete();
                }

                // Xoá toàn bộ mục công việc
                MucCongViec::where('ID_CV', $task->ID_CV)->delete();
            }

            // Xoá toàn bộ công việc
            CongViec::where('ID_KH', $id)->delete();

            // Xoá kế hoạch
            $plan->delete();
        });

        return redirect()->back()->with('success', 'Đã xoá kế hoạch và toàn bộ dữ liệu liên quan!');
    }


    public function deleteGroupTask($id)
    {
        // Lấy thông tin công việc cần xoá
        $task = DB::table('CONG_VIEC')->where('ID_CV', $id)->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Không tìm thấy công việc');
        }

        $idKh = $task->ID_KH;
        $priority = $task->DO_UU_TIEN;

        DB::transaction(function () use ($id, $idKh, $priority) {
            // Lấy các mục công việc
            $subtasks = DB::table('MUC_CONG_VIEC')->where('ID_CV', $id)->get();

            foreach ($subtasks as $subtask) {
                // Xoá cấu hình thông báo liên quan đến từng mục công việc
                DB::table('CAU_HINH_THONG_BAO')->where('ID_MUC', $subtask->ID_MUC)->delete();
            }

            // Xoá các mục công việc
            DB::table('MUC_CONG_VIEC')->where('ID_CV', $id)->delete();

            // Xoá công việc
            DB::table('CONG_VIEC')->where('ID_CV', $id)->delete();

            // Cập nhật độ ưu tiên còn lại
            DB::table('CONG_VIEC')
                ->where('ID_KH', $idKh)
                ->where('DO_UU_TIEN', '>', $priority)
                ->decrement('DO_UU_TIEN');
        });

        return redirect()->route('showGroup')->with('success', 'Đã xóa công việc và cập nhật lại độ ưu tiên.');
    }


    public function deleteGroupSubtask($id)
    {
        // Lấy mục công việc cần xoá
        $muc = DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->first();

        if (!$muc) {
            return redirect()->route('plans.index')->with('error', 'Không tìm thấy mục công việc');
        }

        $idCv = $muc->ID_CV;
        $priority = $muc->DO_UU_TIEN_MUC;

        DB::transaction(function () use ($id, $idCv, $priority) {
            // Xoá cấu hình thông báo liên quan đến mục công việc
            DB::table('CAU_HINH_THONG_BAO')->where('ID_MUC', $id)->delete();

            // Xoá mục công việc
            DB::table('MUC_CONG_VIEC')->where('ID_MUC', $id)->delete();

            // Giảm độ ưu tiên của các mục phía sau
            DB::table('MUC_CONG_VIEC')
                ->where('ID_CV', $idCv)
                ->where('DO_UU_TIEN_MUC', '>', $priority)
                ->decrement('DO_UU_TIEN_MUC');
        });

        return redirect()->route('showGroup')->with('success', 'Đã xoá mục công việc và cập nhật lại độ ưu tiên.');
    }

    public function updateGroupTaskPriority(Request $request, $id)
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

    public function updateGroupSubtaskPriority(Request $request, $id)
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


    public function toggleStatus($id)
    {
        $muc = MucCongViec::findOrFail($id);
        $muc->TRANG_THAI = ! $muc->TRANG_THAI;
        $muc->save();

        return response()->json(['success' => true, 'status' => $muc->TRANG_THAI]);
    }

    public function indexMembers()
    {
        // Lấy thông tin nhóm từ session
        $nhomSession = session('group');
        if (! $nhomSession) {
            return redirect()->back()->with('error', 'Không xác định được nhóm.');
        }

        // Lấy lại thông tin nhóm cùng quan hệ trưởng nhóm
        $nhom = Nhom::with('truongNhom')->find($nhomSession->ID_NHOM);
        // Đặt biến trưởng nhóm để view nhận
        $truongNhom = $nhom->truongNhom;

        // Lấy thành viên nhóm (trừ khóa ngoại nhóm trưởng đã ở trên)
        $thanhVien = DB::table('NHOM_THANH_VIEN')
            ->join('NGUOI_DUNG_CA_NHAN', 'NHOM_THANH_VIEN.ID_USER', '=', 'NGUOI_DUNG_CA_NHAN.ID_USER')
            ->where('NHOM_THANH_VIEN.ID_NHOM', $nhom->ID_NHOM)
            ->get();

        return view('members', compact('nhom', 'truongNhom', 'thanhVien'));
    }

    public function searchGroupMembers(Request $r)
    {
        $search         = $r->input('search_members');
        $id_nhom        = $r->input('id_nhom');
        $id_truong_nhom = $r->input('id_nhom_truong');

        // Danh sách người chưa được mời (ngoại trừ trưởng nhóm và thành viên hiện tại)
        $invited = DB::table('NGUOI_DUNG_CA_NHAN')
            ->when($search, fn($q) => $q->where('HO_TEN', 'like', "%{$search}%"))
            ->where('ID_USER', '!=', $id_truong_nhom)
            ->whereNotIn('ID_USER', function ($sub) use ($id_nhom) {
                $sub->select('ID_USER')
                    ->from('NHOM_THANH_VIEN')
                    ->where('ID_NHOM', $id_nhom);
            })
            ->get();

        // Lấy lại thông tin nhóm và cập nhật session
        $nhom = Nhom::with('truongNhom')->find($id_nhom);
        session(['group' => $nhom]);
        $truongNhom = $nhom->truongNhom;

        // Lấy thành viên nhóm
        $thanhVien = DB::table('NHOM_THANH_VIEN')
            ->join('NGUOI_DUNG_CA_NHAN', 'NHOM_THANH_VIEN.ID_USER', '=', 'NGUOI_DUNG_CA_NHAN.ID_USER')
            ->where('NHOM_THANH_VIEN.ID_NHOM', $id_nhom)
            ->get();

        return view('members', compact('invited', 'nhom', 'truongNhom', 'thanhVien'));
    }

    public function inviteGroup(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:NGUOI_DUNG_CA_NHAN,ID_USER',
            'id_nhom'  => 'required|exists:NHOM_LAM_VIEC,ID_NHOM',
        ]);

        // Kiểm tra đã gửi lời mời chưa
        $exists = DB::table('LOI_MOI')
            ->where('ID_USER', $request->id_user)
            ->where('ID_NHOM', $request->id_nhom)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('invite_error', '⚠️ Bạn đã gửi lời mời cho người này rồi!');
        }

        // Thêm lời mời
        DB::table('LOI_MOI')->insert([
            'ID_USER' => $request->id_user,
            'ID_NHOM' => $request->id_nhom,
        ]);

        return redirect()->back()
            ->with('invite_success', '✅ Đã gửi lời mời thành công!');
    }

    public function leaveGroup($id)
    {
        $userId = Auth::id();

        // Xoá thành viên khỏi nhóm
        DB::table('NHOM_THANH_VIEN')
            ->where('ID_NHOM', $id)
            ->where('ID_USER', $userId)
            ->delete();

        // Xoá luôn lời mời (nếu có)
        DB::table('LOI_MOI')
            ->where('ID_NHOM', $id)
            ->where('ID_USER', $userId)
            ->where('TRANG_THAI_LOI_MOI', 1)
            ->delete();

        return redirect()->route('welcome')->with('info', 'Bạn đã rời nhóm thành công.');
    }

}
