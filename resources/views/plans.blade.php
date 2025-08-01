@extends('layouts.app1')

@section('title', 'Kế hoạch công việc')
@php $user = Auth::user(); @endphp

@section('content')
  <div class="container py-4 mx-auto animate-fade-in">
      <h1 class="flex items-center gap-2 mb-6 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-purple-600 to-pink-500 animate-bounce">
          <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none"
              stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12h6m-3-3v6m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Danh sách Kế hoạch
      </h1>

     <button onclick="showModal('modalAddPlan')"
          class="cursor-pointer bg-gradient-to-r
                  from-red-500 to-purple-500
                  hover:from-red-600 hover:to-purple-600
                  text-white px-5 py-2.5 rounded-lg shadow-xl
                  transition-transform transform hover:scale-110">
          ➕ Thêm Kế hoạch
      </button>

      @foreach($keHoachs as $keHoach)
          <div class="p-4 mt-6 bg-white border border-blue-200 rounded-lg shadow-lg animate-fade-in">
              {{-- Tiêu đề & nút xóa kế hoạch --}}
              <div class="flex items-center justify-between mb-4">
                  <h2 class="text-2xl font-semibold text-gray-800 cursor-pointer editable plan-title hover:underline" data-id="{{ $keHoach->ID_KH }}">
                      {{ $keHoach->TEN_KE_HOACH }}
                  </h2>
                  <button onclick="confirmDelete(
                                      '{{ route('plans.delete', $keHoach->ID_KH) }}',
                                      'Xoá Kế Hoạch',
                                      'Bạn có chắc chắn muốn xoá kế hoạch này không?')"
                          class="text-red-600 cursor-pointer hover:text-red-800">
                      🗑 Xóa Kế Hoạch
                  </button>
              </div>

              {{-- Nút thêm công việc --}}
              <button onclick="showAddTaskModal('{{ $keHoach->ID_KH }}')"
                  class="cursor-pointer bg-gradient-to-r
                  from-green-500 via-blue-400 via-purple-400 to-pink-500
                  hover:from-green-600 hover:via-blue-500 hover:to-pink-600
                  px-5 py-2.5 rounded-lg shadow-xl
                  transition-transform transform hover:scale-110
                  text-sm text-white mb-4 animate-fade-in">
                  + Thêm Công việc
              </button>

              @if($keHoach->cong_viec->isEmpty())
                  <p class="italic text-gray-500">Chưa có công việc nào cho kế hoạch này.</p>
              @else
                  <div class="overflow-x-auto">
                      <div class="flex gap-4 flex-nowrap">
                          @php
                              $colors = [
                                  'from-pink-100 to-pink-200',
                                  'from-purple-100 to-purple-200',
                                  'from-orange-100 to-orange-200',
                                  'from-emerald-100 to-emerald-200',
                                  'from-teal-100 to-teal-200',
                                  'from-fuchsia-100 to-fuchsia-200',
                                  'from-indigo-100 to-indigo-200',
                                  'from-blue-100 to-blue-200',
                                  'from-green-100 to-green-200',
                                  'from-yellow-100 to-yellow-200',
                                  'from-rose-100 to-rose-200',
                                  'from-red-100 to-red-200',
                                  'from-sky-100 to-sky-200',
                                  'from-lime-100 to-lime-200',
                                  'from-fuchsia-100 to-fuchsia-200',
                              ];
                          @endphp


                          @foreach($keHoach->cong_viec as $i => $cv)
                              @php $gradient = $colors[$i % count($colors)]; @endphp

                              <div class="relative min-w-[320px] w-[400px] border-l-4 border-indigo-500
                                          bg-gradient-to-br {{ $gradient }} p-4
                                          rounded-lg shadow-md hover:shadow-xl
                                          transition duration-300 animate-fade-in">
                                  {{-- Công việc --}}
                                  <h3 class="text-lg font-semibold text-gray-800">
                                      <span class="text-indigo-800 cursor-pointer editable task-title hover:underline"
                                              data-id="{{ $cv->ID_CV }}">
                                          {{ $cv->TEN_CV }}
                                      </span>
                                  </h3>

                                  <div class="flex items-center justify-between mb-2">
                                      @php
                                          $tienDo = $cv->TIEN_DO ?? 0;
                                          $color = $tienDo == 100 ? 'text-green-600' : ($tienDo >= 50 ? 'text-yellow-600' : 'text-red-600');
                                      @endphp

                                      <p class="font-semibold {{ $color }}">
                                          Tiến độ: {{ $tienDo }}%
                                      </p>

                                      <button onclick="confirmDelete(
                                                          '{{ route('tasks.delete', $cv->ID_CV) }}',
                                                          'Xoá Công Việc',
                                                          'Bạn có chắc chắn muốn xoá?')"
                                              class="text-sm text-red-500 transition cursor-pointer hover:text-red-700">
                                          🗑 Xóa Công Việc
                                      </button>
                                  </div>

                                  {{-- Thư mục --}}
                                  <ul class="mt-2 ml-5 space-y-2 text-sm list-none">
                                      @foreach($cv->muc_cong_viec as $muc)
                                          @php
                                              $deadline = \Carbon\Carbon::parse($muc->THOI_HAN_HOAN_THANH);
                                              $now = \Carbon\Carbon::now();
                                              $isDone = $muc->TRANG_THAI;
                                              $bgColor = $isDone ? 'bg-green-300' : ($deadline->lt($now) ? 'bg-red-300' : 'bg-blue-100');
                                          @endphp

                                          <li class="relative p-3 rounded shadow-sm transition {{ $bgColor }} hover:brightness-105 hover:scale-105 duration-200 text-left" data-id="{{ $muc->ID_MUC }}">
                                              {{-- Tên mục --}}
                                              <div class="w-full mb-1">
                                                  <span class="font-medium text-gray-800 cursor-pointer editable subtask-title hover:text-indigo-700 hover:underline"
                                                      data-id="{{ $muc->ID_MUC }}">
                                                      {{ $muc->TEN_MUC }}
                                                  </span>
                                              </div>

                                              {{-- Thời hạn --}}
                                              <div class="w-full mb-2 text-center text-gray-600">
                                                  <span class="cursor-pointer editable subtask-deadline" data-id="{{ $muc->ID_MUC }}">
                                                      {{ $muc->THOI_HAN_HOAN_THANH }}
                                                  </span>
                                              </div>

                                              {{-- Nút hành động --}}
                                              <div id="action-buttons-{{ $muc->ID_MUC }}" class="flex justify-start w-full space-x-2 text-xs">
                                                  <button onclick="showViewSubtaskModal('{{ $muc->ID_MUC }}')"
                                                          class="text-blue-600 underline cursor-pointer hover:text-blue-800">
                                                      👁 Xem
                                                  </button>
                                                  <button onclick="showEditSubtaskModal1('{{ $muc->ID_MUC }}')"
                                                          class="text-yellow-600 underline cursor-pointer hover:text-yellow-800">
                                                      ✏️ Chỉnh Sửa
                                                  </button>
                                                  <button onclick="confirmDelete('{{ route('subtasks.delete', $muc->ID_MUC) }}', 'Xoá Thư Mục', 'Bạn có chắc chắn?')"
                                                          class="text-red-600 underline cursor-pointer hover:text-red-800">
                                                      🗑 Xóa
                                                  </button>
                                              </div>

                                              <script>
                                                  window.addEventListener('DOMContentLoaded', () => {
                                                      const el = document.getElementById("action-buttons-{{ $muc->ID_MUC }}");
                                                      if (el && el.offsetWidth > 240) {
                                                          el.classList.remove("justify-start");
                                                          el.classList.add("justify-center");
                                                      }
                                                  });
                                              </script>

                                              {{-- Hiển thị độ ưu tiên --}}
                                                <div class="absolute px-2 py-1 text-xs font-semibold rounded shadow cursor-pointer priority-display bottom-2 right-3 text-black-700 bg-white/60">
                                                    <span ondblclick="editMcvPriority(this, '{{ $muc->ID_MUC }}')">
                                                        🎯 <span class="priority-muc-value">{{ $muc->DO_UU_TIEN_MUC }}</span>
                                                    </span>
                                                </div>
                                          </li>
                                      @endforeach
                                  </ul>

                                  {{-- Thêm mục --}}
                                  <div class="flex justify-center mt-4">
                                      <button onclick="showAddSubTaskModal('{{ $cv->ID_CV }}')"
                                              class="cursor-pointer w-[60%] bg-gradient-to-r from-green-400 via-green-500 to-emerald-500
                                                  text-white py-2 rounded-lg shadow hover:from-green-500 hover:to-emerald-600 hover:scale-105
                                                  transition-transform duration-300 text-sm">
                                          ➕ Thêm Mục
                                      </button>
                                  </div>

                                  {{-- Hiển thị độ ưu tiên --}}
                              <div class="absolute bottom-2 right-3">
                                  <span class="px-2 py-1 text-sm font-semibold rounded shadow cursor-pointer priority-display bg-white/70 hover:ring hover:ring-indigo-300"
                                      ondblclick="editCvPriority(this, '{{ $cv->ID_CV }}')">
                                      ⭐ <span class="priority-value">{{ $cv->DO_UU_TIEN }}</span>
                                  </span>
                              </div>

                              </div>
                          @endforeach
                      </div>
                  </div>
              @endif
          </div>
      @endforeach
  </div>


  <script>
    window.subtaskData = @json(
        $keHoachs->flatMap->cong_viec->flatMap->muc_cong_viec->keyBy('ID_MUC')
    );
    </script>


    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
                location.reload();
            }
        });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setupInlineEditing('{{ csrf_token() }}');
            });


            </script>

@include('Plans.modals')
@endsection

@php($noFooter = true)




