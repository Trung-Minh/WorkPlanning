@extends('layouts.app')

@section('title', 'Thêm thành viên – WorkPlan')
@section('content')
    <main class="w-full pt-2 mx-auto mt-5 sm:w-3/4 md:w-5/10 lg:w-5/10">
        <div class="relative max-w-3xl p-6 mx-auto bg-white shadow-md rounded-2xl">
            <h2 class="mb-4 text-xl font-semibold">Invite Team Members</h2>

            {{-- Hiển thị thông báo --}}
            @if(session('invite_success'))
                <p class="p-2 mb-2 text-sm text-green-700 bg-green-100 rounded">{{ session('invite_success') }}</p>
            @endif
            @if(session('invite_error'))
                <p class="p-2 mb-2 text-sm text-red-700 bg-red-100 rounded">{{ session('invite_error') }}</p>
            @endif

            {{-- Search form --}}
            <form method="POST" action="{{ route('add_members') }}" class="flex mb-4">
                @csrf
                <input type="text" name="search_members" value="{{ old('search_members') }}" placeholder="Search members..."
                    class="flex-1 px-4 py-2 border rounded-l-lg focus:ring-2 focus:ring-orange-400" />
                <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                <input type="hidden" name="id_nhom_truong" value="{{ $nhom->ID_TRUONG_NHOM }}">

                <button type="submit" class="px-4 py-2 text-white bg-orange-500 rounded-r-lg cursor-pointer hover:bg-orange-600">
                    Search
                </button>
            </form>

            {{-- Kết quả search --}}
            @isset($invited)
                @if($invited->count())
                    <ul class="p-2 space-y-2 overflow-y-auto bg-white rounded-lg shadow-sm max-h-60">
                        @foreach($invited as $u)
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('uploads/' . ($u->AVATAR ?? 'avt.jpg')) }}" class="rounded-full w-9 h-9" />
                                    <span>{{ $u->HO_TEN }}</span>
                                </div>
                                {{-- Invite form --}}
                                <form method="POST" action="{{ route('inviteGroup') }}">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{ $u->ID_USER }}">
                                    <input type="hidden" name="id_nhom" value="{{ $nhom->ID_NHOM }}">
                                    <button type="submit" class="px-3 py-1 text-xs text-white bg-green-500 rounded cursor-pointer hover:bg-green-600">
                                        Mời
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm">Không tìm thấy ai phù hợp.</p>
                @endif
            @endisset

            {{-- Members List --}}
            <div class="mt-6">
                <h3 class="mb-2 text-sm font-medium text-gray-500">Members List:</h3>
                <ul class="space-y-4">
                    {{-- Trưởng nhóm --}}
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('uploads/' . ($truongNhom->AVATAR ?? 'avt.jpg')) }}" class="rounded-full w-9 h-9" />
                            <span>{{ $truongNhom->HO_TEN }}</span>
                        </div>
                        <span class="px-3 py-1 text-xs bg-gray-200 rounded-lg">Owner</span>
                    </li>

                    {{-- Các thành viên --}}
                    @foreach($thanhVien as $tv)
                        @if($tv->ID_USER !== $nhom->ID_TRUONG_NHOM)
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('uploads/' . ($tv->AVATAR ?? 'avt.jpg')) }}" class="rounded-full w-9 h-9" />
                                    <span>{{ $tv->HO_TEN }}</span>
                                </div>
                                <span class="px-3 py-1 text-xs text-blue-700 bg-blue-100 rounded-lg">Member</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </main>
@endsection

@php($noFooter = true)
