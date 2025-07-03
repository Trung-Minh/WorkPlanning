@extends('layouts.app')

@section('title', 'Kế hoạch – WorkPlan')

@section('content')
  <div class="flex">
    {{-- Sidebar --}}
    <aside id="sidebar"
    class="hidden xl:block fixed xl:static xl:top-[80px] top-[65px] left-0 z-40 w-64 bg-white h-[calc(100vh-80px)] pt-16 p-4 xl:p-4 overflow-y-auto shadow">
    <h2 class="hidden xl:block text-xl font-bold mb-4">Danh mục</h2>
    <ul id="menuList" class="space-y-2">
      @foreach ($keHoachs as $keHoach)
      <li>
      <a href="#" onclick="loadTemplate({{ $keHoach->ID_KH }}, this)" class="block px-3 py-2 rounded editable-tab"
      data-id="{{ $keHoach->ID_KH }}">
      {{ $keHoach->TEN_KE_HOACH }}
      </a>
      </li>
    @endforeach
    </ul>
    <button onclick="createNewTemplate()"
      class="mt-4 w-full text-sm bg-green-500 text-white py-2 rounded hover:bg-green-600">
      + Thêm mẫu mới
    </button>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 w-full overflow-x-auto">
    <button onclick="addNewColumn()"
      class="fixed top-20 xl:top-24 right-4 z-30 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm shadow">
      + Thêm tiêu đề
    </button>

    <h1 id="pageTitle" class="text-2xl font-bold fixed pt-1 top-20 lg:left-72 left-36">
      Kế hoạch
    </h1>

    <button class="xl:hidden fixed top-20 left-5 z-40 bg-blue-600 text-white px-3 py-2 rounded shadow"
      onclick="toggleSidebar()">
      ☰ Danh mục
    </button>

    <div class="flex p-3 items-center border-b border-gray-300"></div>

    <div id="templateContent" class="flex gap-4 p-4 w-max overflow-x-auto items-start"></div>
    </main>
  </div>

  <div id="formModal" class="fixed inset-0 bg-black bg-opacity-60 items-center justify-center hidden z-50">
    <div class="bg-white text-black rounded p-6 w-full max-w-md">
    <h3 id="formTitle" class="text-xl font-bold mb-4">Nội dung cần làm</h3>
    <textarea id="formTextarea" rows="5" class="w-full p-2 border rounded mb-4"
      placeholder="Nhập nội dung..."></textarea>
    <label class="text-sm font-medium block mb-1">Thời hạn hoàn thành:</label>
    <div class="flex gap-2 mb-4">
      <input type="datetime-local" id="formDeadline" class="w-full p-2 border rounded" />
      <button onclick="clearDeadline()"
      class="px-3 text-sm text-red-600 border border-red-400 rounded hover:bg-red-100">Xóa</button>
    </div>
    <div class="flex justify-between">
      <button onclick="deleteCurrentForm()" class="text-red-500 hover:underline">Xóa mục</button>
      <div class="space-x-2">
      <button onclick="closeForm()" class="px-4 py-2 bg-gray-300 rounded">Hủy</button>
      <button onclick="saveForm()" class="px-4 py-2 bg-blue-600 text-white rounded">Lưu</button>
      </div>
    </div>
    </div>
  </div>

  <div id="server-data" data-kehoach='@json($keHoachs)'></div>
@endsection

@php
  $noFooter = true;
@endphp