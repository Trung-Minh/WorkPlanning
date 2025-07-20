{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Trưởng nhóm – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
    <head><meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <main class="w-full pt-2 mx-auto mt-5 sm:w-3/4 md:w-5/10 lg:w-5/10 ">
   
       <div class="max-w-3xl mx-auto p-6 bg-white rounded-2xl shadow-md">
            <h2 class="text-xl font-semibold mb-4">Invite Team Members</h2>

      

            <!-- Search box -->
            <div class="mb-4 flex">
                <form method="POST" action="{{ url('/search_members') }}" class="mb-4 flex w-full">
                @csrf   
                <input type="text" name="search_members" id="search_members" value="{{ old('search_members') }}" placeholder="Search members..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm" />
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-r-lg hover:bg-orange-600 text-sm">
                    Search
                </button>
                </form>
            </div>

            <div>
                    @php $invited = session('invite'); 
                        $nhom = session('nhom')
                    @endphp

                   @if($invited && $invited->count())
                    <ul class="space-y-2 max-h-60 overflow-y-auto p-2 bg-white rounded-lg shadow-sm">
                        @foreach($invited as $u)
                            <li class="flex items-center gap-3">
                                <img src="{{ asset('uploads/' . ($u->AVATAR ?? 'avt.jpg')) }}" class="w-9 h-9 rounded-full flex-shrink-0" />
                                
                                <span class="font-medium text-sm">{{ $u->HO_TEN }}</span>
                                
                                <form method="POST" action="{{ url('/invite') }}" 
                                    class="ml-auto invite-form flex-shrink-0" 
                                    data-user="{{ $u->ID_USER }}" 
                                    data-nhom="{{ $nhom->ID_NHOM }}">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
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
                    <h3 class="text-sm text-gray-500 font-medium mb-2">Members List:</h3>

                    <ul class="space-y-4">
                    <!-- Member Item -->
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                        <img src="{{ asset('uploads/' . ($user->AVATAR ?? 'avt.jpg')) }}" class="w-9 h-9 rounded-full" />
                        <span class="font-medium text-sm">{{ $user->HO_TEN }}</span></span>
                        </div>
                        <span class="bg-gray-200 text-xs font-medium px-3 py-1 rounded-lg">Owner</span>
                    </li>

                    <!-- Thêm các thành viên khác nếu cần -->
                    </ul>

            </div>

            <div>
                            <form method="POST" action="{{ url('/createGroup') }}" 
                                    class="ml-auto invite-form flex-shrink-0" 
                                    @csrf
                                    inputmode=""
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                        Tạo nhóm
                                    </button>
                            </form>
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
