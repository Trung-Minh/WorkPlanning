{{-- Modal Thêm kế hoạch --}}
<div id="modalAddPlan" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-transparent">
    <form method="POST" action="{{ route('group.plan', ['idNhom' => $nhom->ID_NHOM]) }}"
        class="p-6 border border-blue-200 rounded-lg shadow-xl bg-gradient-to-br from-white via-blue-50 to-pink-50 w-96 animate-fade-in">
        @csrf
        <h2 class="mb-4 text-xl font-bold text-blue-700">📌 Thêm Kế hoạch</h2>
        <input type="text" name="TEN_KE_HOACH" placeholder="Tên kế hoạch"
            class="w-full p-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300" required>
        <div class="flex justify-end gap-2">
            <button type="submit"
                class="px-4 py-2 text-white rounded shadow bg-gradient-to-r from-blue-500 to-indigo-500 hover:brightness-110">Thêm</button>
                @if (session('success'))
                    <div class="px-4 py-2 mt-4 text-green-700 bg-green-100 rounded shadow">
                        ✅ {{ session('success') }}
                    </div>
                @endif
            <button type="button" onclick="hideModal('modalAddPlan')"
                class="px-4 py-2 text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Huỷ</button>
        </div>
    </form>
</div>


{{-- Modal Thêm công việc --}}
<div id="modalAddTask" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-transparent">
    <form method="POST" action="{{ route('group.task') }}"
        class="p-6 border border-green-300 rounded-lg shadow-xl bg-gradient-to-br from-white via-green-50 to-lime-100 w-96 animate-fade-in">
        @csrf
        <input type="hidden" name="ID_KH" id="task_kehoach_id">
        <h2 class="mb-4 text-xl font-bold text-green-700">Thêm Công việc</h2>
        <input type="text" name="TEN_CV" placeholder="Tên công việc" class="w-full p-2 mb-3 border rounded" required>


        <input type="number" name="DO_UU_TIEN" min="1"
            class="w-full p-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400"
            placeholder="Độ ưu tiên (ví dụ: 1, 2, 3...)">
        <p class="mb-3 -mt-3 text-xs italic text-gray-500">
        </p>

        <div class="flex justify-end gap-2">
            <button type="submit"
                class="px-4 py-2 text-white transition rounded bg-gradient-to-r from-green-500 to-emerald-500 hover:scale-105">Thêm</button>
            <button type="button" onclick="hideModal('modalAddTask')"
                class="px-4 py-2 text-gray-600 bg-gray-100 rounded hover:bg-gray-200">Huỷ</button>
        </div>
    </form>
</div>

<!-- Modal Thêm Mục Công Việc -->
<div id="modalAddSubTask" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-transparent">
    <form method="POST" action="{{ route('group.subtask') }}"
            class="w-full max-w-md p-6 scale-95 border border-indigo-200 shadow-2xl bg-gradient-to-tr from-indigo-100 via-white to-pink-100 rounded-2xl animate-fadeIn">
        @csrf
        <input type="hidden" name="ID_CV" id="subtask_congviec_id">

        <h2 class="flex items-center gap-2 mb-4 text-xl font-bold text-indigo-700">
            ✍️ Thêm Mục công việc
        </h2>

        <input type="text" name="TEN_MUC" placeholder="Tên mục" class="w-full p-2 mb-3 border border-purple-300 rounded focus:ring-2 focus:ring-purple-400" required>

        <textarea name="NOI_DUNG_CHI_TIET" placeholder="Nội dung chi tiết" rows="3"
            class="w-full p-2 mb-3 border border-indigo-200 rounded focus:ring-2 focus:ring-indigo-300"></textarea>

        <input type="datetime-local" name="THOI_HAN_HOAN_THANH"
            class="w-full p-2 mb-3 border border-pink-300 rounded focus:ring-2 focus:ring-pink-400" required>

        <input type="number" name="DO_UU_TIEN_MUC" placeholder="Độ ưu tiên" min="1"
            class="w-full p-2 mb-4 border border-yellow-300 rounded focus:ring-2 focus:ring-yellow-400" required>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="hideModal('modalAddSubTask')"
                class="px-4 py-2 text-gray-700 transition bg-gray-200 rounded hover:bg-gray-300">Huỷ</button>
            <button type="submit"
                class="px-4 py-2 text-white transition-all rounded bg-gradient-to-r from-purple-500 to-pink-500 hover:scale-105"> Thêm</button>
        </div>
    </form>
</div>

{{-- Modal Sửa mục công việc --}}
<div id="modalEditSubTask" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-transparent">
    <form id="formEditSubTask" method="POST"
        class="bg-gradient-to-br from-white via-yellow-50 to-orange-100 border border-yellow-300 p-6 rounded-xl w-[90%] max-w-xl shadow-2xl animate-fade-in">
        @csrf
        @method('PUT')

        <h2 class="mb-4 text-xl font-bold text-yellow-700">✏️ Chỉnh Sửa Thư Mục</h2>

        <input type="text" name="TEN_MUC" id="editTenMuc" class="w-full p-2 mb-3 border rounded" required>
        <textarea name="NOI_DUNG_CHI_TIET" id="editNoiDung" class="w-full p-2 mb-3 border rounded" ></textarea>
        <input type="datetime-local" name="THOI_HAN_HOAN_THANH" id="editDeadline"
            class="w-full p-2 mb-3 border rounded" required>
        <input type="number" name="DO_UU_TIEN_MUC" id="editUuTien"
            class="w-full p-2 mb-3 border rounded" min="1" max="10" required>

        <label class="flex items-center mb-3 space-x-2">
            <input type="hidden" name="TRANG_THAI" value="0">
            <input type="checkbox" name="TRANG_THAI" id="editTrangThai" class="accent-green-600" value="1">
            <span>Đánh dấu là hoàn thành</span>
        </label>

        <div class="flex justify-end gap-2">
            <button type="button" onclick="hideModal('modalEditSubTask')"
                class="px-4 py-2 text-gray-800 bg-gray-200 rounded hover:bg-gray-300">Huỷ</button>
            <button type="submit"
                class="px-4 py-2 text-white transition bg-yellow-500 rounded hover:brightness-110">Lưu</button>
        </div>
    </form>
</div>

{{-- Modal Xem chi tiết mục công việc --}}
<div id="modalViewSubTask" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-transparent">
    <div class="p-6 border border-indigo-200 shadow-xl bg-gradient-to-br from-white via-cyan-50 to-indigo-100 rounded-xl w-96 animate-fade-in">
        <h2 class="mb-4 text-xl font-bold text-indigo-700">📄 Chi tiết Mục công việc</h2>
        <p><strong>Tên mục:</strong> <span id="viewTenMuc"></span></p>
        <p><strong>Nội dung:</strong> <span id="viewNoiDung"></span></p>
        <p><strong>Thời hạn:</strong> <span id="viewDeadline"></span></p>
        <p><strong>Độ ưu tiên:</strong> <span id="viewUuTien"></span></p>
        <div class="mt-4 text-right">
            <button onclick="hideModal('modalViewSubTask')"
                class="px-4 py-2 text-white bg-indigo-600 rounded hover:brightness-110">Đóng</button>
        </div>
    </div>
</div>

{{-- Modal xác nhận xoá --}}
<div id="modalConfirmDelete"
    class="fixed inset-0 z-50 flex items-center justify-center hidden bg-transparent">
    <form id="formConfirmDelete" method="POST" onsubmit="return handleDelete(event)"
        class="w-full max-w-sm p-6 bg-white shadow-lg rounded-xl animate-fade-in">
        @csrf
        @method('DELETE')
        <h2 id="modalDeleteTitle" class="mb-4 text-xl font-bold text-red-600">Xác nhận xoá</h2>
        <p id="modalDeleteMessage" class="mb-6 text-gray-700">Bạn có chắc chắn không?</p>

        <div class="flex justify-end gap-3">
            <button type="button" onclick="hideModal('modalConfirmDelete')"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Huỷ</button>
            <button type="submit"
                class="px-4 py-2 text-white transition bg-red-600 rounded hover:brightness-110">Xoá</button>
        </div>
    </form>
</div>


