{{-- Modal Thêm kế hoạch --}}
<div id="modalAddPlan" class="hidden fixed inset-0 bg-transparent flex items-center justify-center z-50">
    <form method="POST" action="{{ route('plans.store') }}"
        class="bg-gradient-to-br from-white via-blue-50 to-pink-50 border border-blue-200 shadow-xl p-6 rounded-lg w-96 animate-fade-in">
        @csrf
        <h2 class="text-xl font-bold text-blue-700 mb-4">📌 Thêm Kế hoạch</h2>
        <input type="text" name="TEN_KE_HOACH" placeholder="Tên kế hoạch"
            class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-300" required>
        <div class="flex justify-end gap-2">
            <button type="submit"
                class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 py-2 rounded hover:brightness-110 shadow">Thêm</button>
                @if (session('success'))
                    <div class="mt-4 px-4 py-2 bg-green-100 text-green-700 rounded shadow">
                        ✅ {{ session('success') }}
                    </div>
                @endif
            <button type="button" onclick="hideModal('modalAddPlan')"
                class="text-gray-600 px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Huỷ</button>
        </div>
    </form>
</div>


{{-- Modal Thêm công việc --}}
<div id="modalAddTask" class="hidden fixed inset-0 bg-transparent flex items-center justify-center z-50">
    <form method="POST" action="{{ route('tasks.store') }}"
        class="bg-gradient-to-br from-white via-green-50 to-lime-100 border border-green-300 shadow-xl p-6 rounded-lg w-96 animate-fade-in">
        @csrf
        <input type="hidden" name="ID_KH" id="task_kehoach_id">
        <h2 class="text-xl font-bold text-green-700 mb-4">Thêm Công việc</h2>
        <input type="text" name="TEN_CV" placeholder="Tên công việc" class="w-full p-2 border rounded mb-3" required>


        <input type="number" name="DO_UU_TIEN" min="1"
            class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-indigo-400"
            placeholder="Độ ưu tiên (ví dụ: 1, 2, 3...)">
        <p class="text-xs text-gray-500 italic -mt-3 mb-3">
        </p>

        <div class="flex justify-end gap-2">
            <button type="submit"
                class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-2 rounded hover:scale-105 transition">Thêm</button>
            <button type="button" onclick="hideModal('modalAddTask')"
                class="text-gray-600 px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Huỷ</button>
        </div>
    </form>
</div>

<!-- Modal Thêm Mục Công Việc -->
<div id="modalAddSubTask" class="hidden fixed inset-0 bg-transparent flex items-center justify-center z-50">
    <form method="POST" action="{{ route('subtasks.store') }}"
          class="bg-gradient-to-tr from-indigo-100 via-white to-pink-100 border border-indigo-200 shadow-2xl rounded-2xl p-6 w-full max-w-md scale-95 animate-fadeIn">
        @csrf
        <input type="hidden" name="ID_CV" id="subtask_congviec_id">

        <h2 class="text-xl font-bold text-indigo-700 mb-4 flex items-center gap-2">
            ✍️ Thêm Mục công việc
        </h2>

        <input type="text" name="TEN_MUC" placeholder="Tên mục" class="w-full p-2 border border-purple-300 rounded mb-3 focus:ring-2 focus:ring-purple-400" required>

        <textarea name="NOI_DUNG_CHI_TIET" placeholder="Nội dung chi tiết" rows="3"
                  class="w-full p-2 border border-indigo-200 rounded mb-3 focus:ring-2 focus:ring-indigo-300"></textarea>

        <input type="datetime-local" name="THOI_HAN_HOAN_THANH"
               class="w-full p-2 border border-pink-300 rounded mb-3 focus:ring-2 focus:ring-pink-400" required>

        <input type="number" name="DO_UU_TIEN_MUC" placeholder="Độ ưu tiên" min="1" max="10"
               class="w-full p-2 border border-yellow-300 rounded mb-4 focus:ring-2 focus:ring-yellow-400" required>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="hideModal('modalAddSubTask')"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition">Huỷ</button>
            <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded hover:scale-105 transition-all"> Thêm</button>
        </div>
    </form>
</div>



{{-- Modal Sửa mục công việc --}}
<div id="modalEditSubTask" class="hidden fixed inset-0 bg-transparent z-50 flex justify-center items-center">
    <form id="formEditSubTask" method="POST"
        class="bg-gradient-to-br from-white via-yellow-50 to-orange-100 border border-yellow-300 p-6 rounded-xl w-[90%] max-w-xl shadow-2xl animate-fade-in">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-bold text-yellow-700 mb-4">✏️ Chỉnh Sửa Thư Mục</h2>

        <input type="text" name="TEN_MUC" id="editTenMuc" class="w-full border rounded p-2 mb-3" required>
        <textarea name="NOI_DUNG_CHI_TIET" id="editNoiDung" class="w-full border rounded p-2 mb-3" ></textarea>
        <input type="datetime-local" name="THOI_HAN_HOAN_THANH" id="editDeadline"
            class="w-full border rounded p-2 mb-3" required>
        <input type="number" name="DO_UU_TIEN_MUC" id="editUuTien"
            class="w-full border rounded p-2 mb-3" min="1" max="10" required>

        <label class="flex items-center space-x-2 mb-3">
            <input type="checkbox" name="TRANG_THAI" id="editTrangThai" class="accent-green-600">
            <span>Đánh dấu là hoàn thành</span>
        </label>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="hideModal('modalEditSubTask')"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Huỷ</button>
            <button type="submit"
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:brightness-110 transition">Lưu</button>
        </div>
    </form>
</div>



{{-- Modal Xem chi tiết mục công việc --}}
<div id="modalViewSubTask" class="hidden fixed inset-0 bg-transparent flex items-center justify-center z-50">
    <div class="bg-gradient-to-br from-white via-cyan-50 to-indigo-100 border border-indigo-200 p-6 rounded-xl w-96 shadow-xl animate-fade-in">
        <h2 class="text-xl font-bold text-indigo-700 mb-4">📄 Chi tiết Mục công việc</h2>
        <p><strong>Tên mục:</strong> <span id="viewTenMuc"></span></p>
        <p><strong>Nội dung:</strong> <span id="viewNoiDung"></span></p>
        <p><strong>Thời hạn:</strong> <span id="viewDeadline"></span></p>
        <p><strong>Độ ưu tiên:</strong> <span id="viewUuTien"></span></p>
        <div class="text-right mt-4">
            <button onclick="hideModal('modalViewSubTask')"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:brightness-110">Đóng</button>
        </div>
    </div>
</div>


{{-- Modal xác nhận xoá --}}
<!-- Modal xác nhận xoá -->
<div id="modalConfirmDelete"
    class="hidden fixed inset-0 bg-transparent z-50 flex justify-center items-center">
    <form id="formConfirmDelete" method="POST" onsubmit="return handleDelete(event)"
        class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full animate-fade-in">
        @csrf
        @method('DELETE')
        <h2 id="modalDeleteTitle" class="text-xl font-bold text-red-600 mb-4">Xác nhận xoá</h2>
        <p id="modalDeleteMessage" class="text-gray-700 mb-6">Bạn có chắc chắn không?</p>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="hideModal('modalConfirmDelete')"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Huỷ</button>
            <button type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded hover:brightness-110 transition">Xoá</button>
        </div>
    </form>
</div>


