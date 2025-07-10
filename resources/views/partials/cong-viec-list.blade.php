
    @foreach($congViecs as $cv)
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold">{{ $cv->NOI_DUNG_CV }}</h2>
        <button onclick="openModal('{{ $cv->ID_CV }}')" class="text-sm text-blue-600 underline">
        + Thêm mục
        </button>
    </div>
    @endforeach