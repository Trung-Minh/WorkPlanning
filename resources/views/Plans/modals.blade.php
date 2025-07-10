{{-- Modal Thêm kế hoạch --}}
<div id="modalAddPlan" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <form method="POST" action="{{ route('plans.store') }}" class="bg-white p-6 rounded shadow w-96">
        @csrf
        <h2 class="text-lg font-bold mb-4">Thêm Kế hoạch</h2>
        <input type="text" name="TEN_KE_HOACH" placeholder="Tên kế hoạch" class="w-full p-2 border mb-4" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Thêm</button>
        <button type="button" onclick="hideModal('modalAddPlan')" class="ml-2 text-gray-600">Huỷ</button>
    </form>
</div>

{{-- Modal Thêm công việc --}}
<div id="modalAddTask" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
<form method="POST" action="{{ route('tasks.store') }}" class="bg-white p-6 rounded shadow w-96">
    @csrf
    <input type="hidden" name="ID_KH" id="task_kehoach_id">
    <h2 class="text-lg font-bold mb-4">Thêm Công việc</h2>
    <input type="text" name="TEN_CV" placeholder="Tên công việc" class="w-full p-2 border mb-3" required>
    <input type="number" name="TIEN_DO" placeholder="Tiến độ (%)" min="0" max="100" class="w-full p-2 border mb-3" required>
    <input type="number" name="DO_UU_TIEN" placeholder="Độ ưu tiên" min="1" max="10" class="w-full p-2 border mb-4">
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Thêm</button>
    <button type="button" onclick="hideModal('modalAddTask')" class="ml-2 text-gray-600">Huỷ</button>
</form>
</div>

{{-- Modal Thêm mục công việc --}}
<div id="modalAddSubTask" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
<form method="POST" action="{{ route('subtasks.store') }}" class="bg-white p-6 rounded shadow w-96">
    @csrf
    <input type="hidden" name="ID_CV" id="subtask_congviec_id">
    <h2 class="text-lg font-bold mb-4">Thêm Mục công việc</h2>
    <input type="text" name="TEN_MUC" placeholder="Tên mục" class="w-full p-2 border mb-3" required>
    <textarea name="NOI_DUNG_CHI_TIET" placeholder="Nội dung chi tiết" rows="3" class="w-full p-2 border mb-3"></textarea>
    <input type="datetime-local" name="THOI_HAN_HOAN_THANH" class="w-full p-2 border mb-3">
    <input type="number" name="DO_UU_TIEN_MUC" placeholder="Độ ưu tiên" min="1" max="10" class="w-full p-2 border mb-4">
    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Thêm</button>
    <button type="button" onclick="hideModal('modalAddSubTask')" class="ml-2 text-gray-600">Huỷ</button>
</form>
</div>

{{-- Modal Sửa mục công việc --}}
<div id="modalEditSubTask" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <form method="POST" action="" id="formEditSubTask" class="bg-white p-6 rounded shadow w-96">
        @csrf @method('PUT')
        <h2 class="text-lg font-bold mb-4">Sửa Mục công việc</h2>
        <input type="text" name="TEN_MUC" id="editTenMuc" placeholder="Tên mục" class="w-full p-2 border mb-3" required>
        <textarea name="NOI_DUNG_CHI_TIET" id="editNoiDung" rows="3" placeholder="Nội dung chi tiết" class="w-full p-2 border mb-3"></textarea>
        <input type="datetime-local" name="THOI_HAN_HOAN_THANH" id="editDeadline" class="w-full p-2 border mb-3">
        <input type="number" name="DO_UU_TIEN_MUC" id="editUuTien" min="1" max="10" class="w-full p-2 border mb-4">
        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Lưu</button>
        <button type="button" onclick="hideModal('modalEditSubTask')" class="ml-2 text-gray-600">Huỷ</button>
    </form>
</div>

{{-- Modal Xem chi tiết mục công việc --}}
<div id="modalViewSubTask" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-lg font-bold mb-4">📄 Chi tiết Mục công việc</h2>
        <p><strong>Tên mục:</strong> <span id="viewTenMuc"></span></p>
        <p><strong>Nội dung:</strong> <span id="viewNoiDung"></span></p>
        <p><strong>Thời hạn:</strong> <span id="viewDeadline"></span></p>
        <p><strong>Độ ưu tiên:</strong> <span id="viewUuTien"></span></p>
        <button onclick="hideModal('modalViewSubTask')" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded">Đóng</button>
    </div>
</div>




{{-- Modal xác nhận xoá dùng chung --}}
<div id="modalConfirmDelete" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <form method="POST" id="formConfirmDelete" class="bg-white p-6 rounded shadow w-96">
        @csrf
        @method('DELETE')
        <h2 class="text-lg font-bold mb-4 text-red-600" id="modalDeleteTitle">Xác nhận xoá</h2>
        <p id="modalDeleteMessage">Bạn có chắc chắn muốn xoá không?</p>
        <div class="mt-4 flex justify-end gap-2">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Xoá</button>
            <button type="button" onclick="hideModal('modalConfirmDelete')" class="px-4 py-2 bg-gray-300 rounded">Huỷ</button>
        </div>
    </form>
</div>