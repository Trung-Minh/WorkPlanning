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
                    class="mb-4 text-xl font-semibold cursor-pointer">
                </h1>

                <!-- Ô input auto-resize -->
                <input x-show="editing"
                    x-model="tenMoi"
                    @input="updateWidth()"
                    @keydown.enter="tenNhom = tenMoi; editing = false"
                    @blur="tenNhom = tenMoi; editing = false"
                    class="px-2 py-1 text-lg text-center transition-all border rounded"
                    :style="'width: ' + width + 'px'"
                    type="text"
                    autofocus />
                <div>
                    <form method="POST" action="{{ url('/groups') }}" class="absolute bottom-0 right-0 mb-2 mr-2">
                        @csrf
                        <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                        <input type="hidden" name="ten_nhom" x-model="tenNhom">
                        <button type="submit"
                            class="px-5 py-3 text-sm font-bold text-white bg-blue-600 rounded-full shadow-lg hover:bg-blue-700">
                            + Tạo nhóm
                        </button>
                    </form>
                </div>
            </div>

            <h2 class="mb-4 text-xl font-semibold">Invite Team Members</h2>

            <!-- Search box -->
            <div class="flex mb-4">
                <form method="POST" action="{{ url('/search_members') }}" class="flex w-full mb-4">
                @csrf
                <input type="text" name="search_members" id="search_members" value="{{ old('search_members') }}" placeholder="Search members..."
                    class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-orange-400" />
                <button type="submit" class="px-4 py-2 text-sm text-white bg-orange-500 rounded-r-lg hover:bg-orange-600">
                    Search
                </button>
                </form>
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

@endsection

@php($noFooter = true)
