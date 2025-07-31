{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Trưởng nhóm – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <!-- Thêm Alpine.js nếu chưa có -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @php
        $nhom = session('nhom')
    @endphp

    <main class="w-full pt-2 mx-auto mt-5 sm:w-3/4 md:w-5/10 lg:w-5/10">
        <div class="relative max-w-3xl p-6 mx-auto bg-white shadow-md rounded-2xl">

            <!-- Editable heading -->

                <div x-data="{
                        editing: false,
                        tenNhom: '{{ $nhom->TEN_NHOM }}',
                        tenMoi: '',
                        width: 0,
                        updateWidth() {
                            this.width = this.tenMoi.length * 10 + 30; // công thức tạm tính
                        }
                    }"
                    x-init="tenMoi = tenNhom; updateWidth();"
                    class="text-center">

                    <!-- Hiển thị <h1> nếu không chỉnh sửa -->
                    <h1 x-show="!editing"
                        @dblclick="editing = true; updateWidth()"
                        x-text="tenNhom"
                        class="text-xl font-semibold mb-4 cursor-pointer">
                    </h1>

                    <!-- Ô input auto-resize -->
                    <input x-show="editing"
                        x-model="tenMoi"
                        @input="updateWidth()"
                        @keydown.enter="tenNhom = tenMoi; editing = false"
                        @blur="tenNhom = tenMoi; editing = false"
                        class="border rounded px-2 py-1 text-lg text-center transition-all"
                        :style="'width: ' + width + 'px'"
                        type="text"
                        autofocus />
                    <div>
                        <form method="POST" action="{{ url('/groups') }}" class="absolute bottom-0 right-0 mb-2 mr-2">
                            @csrf
                            <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                            <input type="hidden" name="ten_nhom" x-model="tenNhom">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-5 rounded-full shadow-lg text-sm">
                                + Tạo nhóm
                            </button>
                        </form>

                    </div>
            
         

                    <h2 class="text-xl font-semibold mb-4 text-left">Invite Team Members</h2>

                <!-- Search box -->
                        <div class="mb-4 flex">
                            <form method="POST" action="{{ url('/search_members') }}" class="mb-4 flex w-full">
                            @csrf   
                            <input type="text" name="search_members" id="search_members" value="{{ old('search_members') }}" placeholder="Search members..."
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm" />
                                <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                                <input type="hidden" name="id_nhom_truong" value="{{ $nhom->ID_NHOM_TRUONG}}">
                                <input type="hidden" name="ten_nhom" x-model="tenNhom">
                            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-r-lg hover:bg-orange-600 text-sm">
                                Search
                            </button>
                            </form>
                        </div>
                </div>

            <div>
                @php $invited = session('invite'); @endphp

                @if($invited && $invited->count())
                <ul class="p-2 space-y-2 overflow-y-auto bg-white rounded-lg shadow-sm max-h-60">
                    @foreach($invited as $u)
                        <li class="flex items-center gap-3">
                            <img src="{{ asset('uploads/' . ($u->AVATAR ?? 'avt.jpg')) }}" class="flex-shrink-0 rounded-full w-9 h-9" />

                            <span class="text-sm font-medium">{{ $u->HO_TEN }}</span>

                            <form method="POST" action="{{ url('/invite') }}"
                                class="flex-shrink-0 ml-auto invite-form"
                                data-user="{{ $u->ID_USER }}"
                                data-nhom="{{ $nhom->ID_NHOM }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 text-xs text-white bg-green-500 rounded hover:bg-green-600">
                                    Mời
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                @else
                    <p>Không tìm thấy người dùng.</p>
                    <br>
                @endif
            </div>


            <!-- Members List -->
            <div>
                <br>
                <h3 class="mb-2 text-sm font-medium text-gray-500">Members List:</h3>

                <ul class="space-y-4">
                    <!-- Member Item -->
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('uploads/' . ($user->AVATAR ?? 'avt.jpg')) }}" class="rounded-full w-9 h-9" />
                            <span class="text-sm font-medium">{{ $user->HO_TEN }}</span></span>
                        </div>

                        <span class="px-3 py-1 text-xs font-medium bg-gray-200 rounded-lg">Owner</span>
                    </li>
                    <li>
                        <br>
                    </li>
                </ul>
            </div>       

      <div id="leave-confirm-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-2xl text-center max-w-md w-full translate-y-[-40px]">
        

        <!-- Tiêu đề -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6 whitespace-normal leading-snug">
            Cảnh báo: Hành động này sẽ hủy quá trình tạo nhóm hiện tại. Bạn có chắc muốn tiếp tục?
        </h2>

        <!-- Nút hành động -->
        <div class="flex justify-center gap-4">
            <button id="cancel-leave"
                    class="bg-gray-300 px-5 py-2 rounded-full hover:bg-gray-400 transition">
                Hủy
            </button>


            <form id="delete-form" method="POST" action="{{ url('/delete_group') }}">
                @csrf
                <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                <input type="hidden" name="redirect_to" id="redirect_to">
            </form>

            <button type="button" id="confirm-leave"
                    class="bg-red-500 text-white px-5 py-2 rounded-full hover:bg-red-600 transition">
                Tiếp tục
            </button>


        </div>
    </div>
</div>


    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.invite-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Ngăn reload

                    const button = this.querySelector('button');
                    const id_user = this.dataset.user;
                    const id_nhom = this.dataset.nhom;

                    fetch('/invite', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ id_user, id_nhom })
                    })
                    .then(res => {
                        if (res.ok) {
                            button.disabled = true;
                            button.classList.add('opacity-50');
                            button.innerText = 'Đã mời';
                        } else {
                            alert('Lỗi khi gửi lời mời!');
                        }
                    })
                    .catch(err => {
                        alert('Lỗi mạng!');
                        console.error(err);
                    });
                });
            });
        });
    </script>
    <!-- modal rời khỏi -->
   <script>
        document.addEventListener('DOMContentLoaded', function () {

            if (true) {
                let formSubmitted = false;
                let pendingUrl = null;

                // Gắn sự kiện cho form submit
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function () {
                        formSubmitted = true;
                    });
                });

                // Gắn sự kiện cho tất cả link (a) để chặn rời trang
                document.querySelectorAll('a[href]').forEach(link => {
                    link.addEventListener('click', function (e) {
                        if (formSubmitted) return; // Nếu form đã submit thì cho đi
                        const href = link.getAttribute('href');
                        if (!href.startsWith('#') && !href.startsWith('javascript:')) {
                            e.preventDefault();
                            pendingUrl = href;
                            document.getElementById('leave-confirm-modal').classList.remove('hidden');
                        }
                    });
                });

                window.addEventListener("pageshow", function (event) {
                    const isBack = event.persisted || performance.getEntriesByType("navigation")[0]?.type === "back_forward";

                    if (isBack) {

                        // Cảnh báo khi quay lại trang bằng nút Back
                        pendingUrl = document.referrer || '/'; // Gán referrer làm đường dẫn quay lại
                        const modal = document.getElementById('leave-confirm-modal');
                        if (modal) {
                            modal.classList.remove('hidden');
                        }
                    }
                });


                // Nút "Tiếp tục"
                document.getElementById('confirm-leave').addEventListener('click', function () {
                    document.getElementById('redirect_to').value = pendingUrl; // hoặc dynamic
                    document.getElementById('delete-form').submit();
                });


                // Nút "Hủy"
                document.getElementById('cancel-leave').addEventListener('click', function () {
                    document.getElementById('leave-confirm-modal').classList.add('hidden');
                    pendingUrl = null;
                });

            }
        });
    </script>


@endsection

@php($noFooter = true)
