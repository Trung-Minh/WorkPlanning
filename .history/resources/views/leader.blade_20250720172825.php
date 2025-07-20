{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Trưởng nhóm – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
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
                                <span class="font-medium text-sm">{{ $nhom }}</span>

                                <form method="POST" action="{{ url('/invite') }}" class="ml-auto">
                                    @csrf
                                    <input type="hidden" name="id_users" value="{{ $u->ID_USER }}">
                                    <input type="hidden" name="id_nhom" value="{{ $nhom }}">
                                    
                                    <button type="submit"
                                        class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600"
                                        onclick="this.disabled = true; this.classList.add('opacity-50'); this.innerText = 'Đã mời';">
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

                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/36?u=2" class="w-9 h-9 rounded-full" />
                        <span class="font-medium text-sm">Ronald Richards</span>
                        </div>
                        <div class="relative">
                        <select class="bg-orange-100 text-orange-700 text-xs font-semibold px-3 py-1 rounded-lg hover:bg-orange-200">
                            <option selected>Can Edit</option>
                            <option>Can View</option>
                            <option class="text-red-500">Remove</option>
                        </select>
                        </div>
                    </li>

                    <!-- Thêm các thành viên khác nếu cần -->
                    </ul>
            </div>

       </div>
    </main>
@endsection

@php($noFooter = true)
