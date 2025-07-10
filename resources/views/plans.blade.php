@extends('layouts.app')

@section('title', 'Kế hoạch công việc')

@section('content')
<div class="container mx-auto py-4 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-transparent bg-clip-text 
               bg-gradient-to-r from-blue-700 via-purple-600 to-pink-500 
               mb-6 flex items-center gap-2 animate-bounce">
        <svg class="w-8 h-8 text-blue-600 animate-pulse" fill="none" 
             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M9 12h6m-3-3v6m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Danh sách Kế hoạch
    </h1>

    <button onclick="showModal('modalAddPlan')" 
            class="cursor-pointer bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 
                   hover:from-indigo-600 hover:to-pink-600 text-white 
                   px-5 py-2.5 rounded-lg shadow-xl 
                   transition-transform transform hover:scale-110">
        ➕ Thêm Kế hoạch
    </button>

    @foreach($keHoachs as $keHoach)
        <div class="border border-blue-200 bg-white p-4 mt-6 rounded-lg shadow-lg animate-fade-in">
            {{-- Tiêu đề & nút xóa kế hoạch --}}
            <div class="cursor-pointer flex justify-between items-center mb-4">
                <h2 class="hover:underline text-2xl font-semibold text-gray-800">
                    {{ $keHoach->TEN_KE_HOACH }}
                </h2>
                <button onclick="confirmDelete(
                                    '{{ route('plans.delete', $keHoach->ID_KH) }}', 
                                    'Xoá Kế Hoạch', 
                                    'Bạn có chắc chắn muốn xoá kế hoạch này không?')"
                        class="text-red-600 hover:text-red-800">
                    🗑 Xóa Kế Hoạch
                </button>
            </div>

            {{-- Nút thêm công việc --}}
            <button onclick="showAddTaskModal('{{ $keHoach->ID_KH }}')" 
                    class="cursor-pointer bg-gradient-to-r from-indigo-500 via-white-300 to-yellow-500 
                   hover:from-indigo-300 hover:to-pink-500 text-white 
                   px-5 py-2.5 rounded-lg shadow-xl 
                   transition-transform transform hover:scale-110 text-sm text-blue-600 mb-4 animate-fade-in">
                + Thêm Công việc
            </button>

            @if($keHoach->cong_viec->isEmpty())
                <p class="italic text-gray-500">Chưa có công việc nào cho kế hoạch này.</p>
            @else
                <div class="overflow-x-auto">
                    <div class="flex flex-nowrap gap-4">
                        @php
                            $colors = [
                                'from-pink-100 to-pink-200',
                                'from-purple-100 to-purple-200',
                                'from-indigo-100 to-indigo-200',
                                'from-blue-100 to-blue-200',
                                'from-green-100 to-green-200',
                                'from-yellow-100 to-yellow-200',
                                'from-rose-100 to-rose-200',
                                'from-teal-100 to-teal-200',
                            ];
                        @endphp

                        @foreach($keHoach->cong_viec as $i => $cv)
                            @php $gradient = $colors[$i % count($colors)]; @endphp

                            <div class="min-w-[320px] w-[400px] border-l-4 border-indigo-500 
                                        bg-gradient-to-br {{ $gradient }} p-4 
                                        rounded-lg shadow-md hover:shadow-xl 
                                        transition duration-300 animate-fade-in">
                                {{-- Công việc --}}
                                <h3 class="text-lg font-semibold text-gray-800">
                                    <span class="editable task-title text-indigo-800 hover:underline cursor-pointer" 
                                          data-id="{{ $cv->ID_CV }}">
                                        {{ $cv->TEN_CV }}
                                    </span>
                                </h3>
                                <div class="flex justify-between items-center mb-2">
                                  <span class="cursor-pointer editable task-progress text-green-700 font-semibold animate-pulse" 
                                              data-id="{{ $cv->ID_CV }}">({{ $cv->TIEN_DO }}%)</span>
                                  <button onclick="confirmDelete(
                                                      '{{ route('tasks.delete', $cv->ID_CV) }}', 
                                                      'Xoá Công Việc', 
                                                      'Bạn có chắc chắn muốn xoá?')"
                                          class="cursor-pointer text-sm text-red-500 hover:text-red-700 transition">
                                      🗑 Xóa Công Việc
                                  </button>
                                </div>

                                {{-- Thư mục --}}
                                <ul class="list-none list-disc ml-5 text-sm mt-2 space-y-2">
                                    @foreach($cv->muc_cong_viec as $muc)
                                        @php
                                            $deadline = \Carbon\Carbon::parse($muc->THOI_HAN_HOAN_THANH);
                                            $now = \Carbon\Carbon::now();
                                            $isDone = $muc->TRANG_THAI;
                                            if ($isDone) {
                                                $bgColor = 'bg-green-100';
                                            } elseif ($deadline->lt($now)) {
                                                $bgColor = 'bg-red-100';
                                            } else {
                                                $bgColor = 'bg-blue-100';
                                            }
                                        @endphp
                                        <li class="p-3 rounded shadow-sm transition {{ $bgColor }} hover:brightness-105 hover:scale-105 duration-200 text-left" data-id="{{ $muc->ID_MUC }}">
  
                                          {{-- Tên mục --}}
                                          <div class="w-full mb-1">
                                            <span class="cursor-pointer editable subtask-title font-medium text-gray-800 hover:text-indigo-700 hover:underline cursor-pointer"
                                                  data-id="{{ $muc->ID_MUC }}">
                                              {{ $muc->TEN_MUC }}
                                            </span>
                                          </div>

                                          {{-- Thời hạn --}}
                                          <div class="w-full mb-2 text-gray-600 text-center">
                                            <span class="cursor-pointer editable subtask-deadline" data-id="{{ $muc->ID_MUC }}">
                                              {{ $muc->THOI_HAN_HOAN_THANH }}
                                            </span>
                                          </div>

                                          {{-- Nút hành động --}}
                                          <div class="w-full flex justify-center space-x-4 text-xs">
                                            <button onclick="showViewSubtaskModal('{{ $muc->ID_MUC }}')"
                                                    class="cursor-pointer text-blue-600 underline hover:text-blue-800">
                                              👁 Xem
                                            </button>
                                            <button onclick="showEditSubtaskModal1('{{ $muc->ID_MUC }}')"
                                                    class="cursor-pointer text-yellow-600 underline hover:text-yellow-800">
                                              ✏️ Chỉnh Sửa
                                            </button>
                                            <button onclick="confirmDelete('{{ route('subtasks.delete', $muc->ID_MUC) }}', 'Xoá Thư Mục', 'Bạn có chắc chắn?')"
                                                    class="cursor-pointer text-red-600 underline hover:text-red-800">
                                              🗑 Xóa
                                            </button>
                                          </div>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Thêm thư mục --}}
                                <div class="mt-4 flex justify-center">
                                    <button onclick="showAddSubTaskModal('{{ $cv->ID_CV }}')" 
                                            class="cursor-pointer w-[60%] bg-gradient-to-r from-green-400 via-green-500 to-emerald-500 
                                                   text-white py-2 rounded-lg shadow hover:from-green-500 hover:to-emerald-600 hover:scale-105 
                                                   transition-transform duration-300 text-sm">
                                        ➕ Thêm Thư Mục
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>

@include('Plans.modals')

<script>
    window.subtaskData = @json(
        $keHoachs->flatMap->cong_viec->flatMap->muc_cong_viec->keyBy('ID_MUC')
    );
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupInlineEditing('{{ csrf_token() }}');
    });
</script>
@endsection

@php($noFooter = true)