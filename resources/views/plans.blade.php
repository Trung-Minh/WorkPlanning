@extends('layouts.app')

@section('title', 'Kế hoạch – WorkPlan')

@section('content')
  <div class="flex">
    {{-- Sidebar --}}
    <aside id="sidebar"
    class="hidden xl:block fixed xl:static xl:top-[80px] top-[65px] left-0 z-40 w-64 bg-white h-[calc(100vh-80px)] pt-16 p-4 xl:p-4 overflow-y-auto shadow">
    <h2 class="hidden mb-4 text-xl font-bold xl:block">Danh mục</h2>
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
      class="w-full py-2 mt-4 text-sm text-white bg-green-500 rounded hover:bg-green-600">
      + Thêm mẫu mới
    </button>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 w-full overflow-x-auto">
    <button onclick="addNewColumn()"
      class="fixed z-30 px-4 py-2 text-sm text-white bg-blue-500 rounded shadow top-20 xl:top-24 right-4 hover:bg-blue-600">
      + Thêm tiêu đề
    </button>

    <h1 id="pageTitle" class="fixed pt-1 text-2xl font-bold top-20 lg:left-72 left-36">
      Kế hoạch
    </h1>

    <button class="fixed z-40 px-3 py-2 text-white bg-blue-600 rounded shadow xl:hidden top-20 left-5"
      onclick="toggleSidebar()">
      ☰ Danh mục
    </button>

    <div class="flex items-center p-3 border-b border-gray-300"></div>

    <div id="templateContent" class="flex items-start gap-4 p-4 overflow-x-auto w-max"></div>
    </main>
</div>

    <div id="formModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-60">
    <div class="w-full max-w-md p-6 text-black bg-white rounded">
    <h3 id="formTitle" class="mb-4 text-xl font-bold">Nội dung cần làm</h3>
    <textarea id="formTextarea" rows="5" class="w-full p-2 mb-4 border rounded"
        placeholder="Nhập nội dung..."></textarea>
    <label class="block mb-1 text-sm font-medium">Thời hạn hoàn thành:</label>
    <div class="flex gap-2 mb-4">
      <input type="datetime-local" id="formDeadline" class="w-full p-2 border rounded" />
      <button onclick="clearDeadline()"
      class="px-3 text-sm text-red-600 border border-red-400 rounded hover:bg-red-100">Xóa</button>
    </div>
    <div class="flex justify-between">
      <button onclick="deleteCurrentForm()" class="text-red-500 hover:underline">Xóa mục</button>
      <div class="space-x-2">
      <button onclick="closeForm()" class="px-4 py-2 bg-gray-300 rounded">Hủy</button>
      <button onclick="saveForm()" class="px-4 py-2 text-white bg-blue-600 rounded">Lưu</button>
      </div>
    </div>
    </div>
  </div>

    <div id="server-data" data-kehoach='@json($keHoachs)'></div>
@endsection

@php
  $noFooter = true;
@endphp
