<!-- <div class="p-4 bg-white border border-blue-200 rounded-lg shadow-lg">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ $keHoach->TEN_KE_HOACH }}
        </h2>
        <button onclick="confirmDelete('{{ route('plans.delete', $keHoach->ID_KH) }}', 'Xóa Kế Hoạch', 'Bạn có chắc chắn?')"
                class="text-red-600 hover:text-red-800 text-sm">
            🗑 Xóa
        </button>
    </div>

    <button onclick="showAddTaskModal('{{ $keHoach->ID_KH }}')"
            class="bg-gradient-to-r from-green-400 to-green-600 text-white px-4 py-2 rounded shadow hover:scale-105">
        ➕ Thêm Công việc
    </button>

    @if($keHoach->cong_viec->isEmpty())
        <p class="mt-4 italic text-gray-500">Chưa có công việc nào.</p>
    @else
        <div class="mt-4 space-y-4">
            @foreach($keHoach->cong_viec as $cv)
                <div class="p-4 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded shadow">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold text-gray-800">{{ $cv->TEN_CV }}</h3>
                        <span class="text-xs font-medium bg-white px-2 py-1 rounded shadow">⭐ {{ $cv->DO_UU_TIEN }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Tiến độ: {{ $cv->TIEN_DO }}%</p>

                    @if($cv->muc_cong_viec->isNotEmpty())
                        <ul class="mt-2 ml-4 list-disc text-sm text-gray-700">
                            @foreach($cv->muc_cong_viec as $muc)
                                <li>{{ $muc->TEN_MUC }} @if($muc->TRANG_THAI) ✅ @endif</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div> -->
