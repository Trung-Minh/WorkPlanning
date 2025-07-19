@extends('layouts.app')

@section('title', 'Nhắc nhở – WorkPlan')

@section('content')
<h1><strong>DANH SÁCH NHẮC NHỞ</strong></h1>
@foreach ($reminders as $reminder)
<h2>{{ $reminder->TEN_KE_HOACH }}</h2>

@foreach ($reminder->congViecs as $congViec)
<h4>- {{ $congViec->TEN_CV }}</h4>

<ul>
  @foreach ($congViec->mucCongViecs as $muc)
  <li>{{ $muc->TEN_MUC }}</li>
  <li>
    <p>{{ $muc->NOI_DUNG_CHI_TIET }}</p>
  </li>
  <br />
  @if ($muc->THOI_HAN_HOAN_THANH)
  {{ $muc->THOI_HAN_HOAN_THANH->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s') }}
  @else
  <span class="text-gray-500">Chưa cập nhật</span>
  @endif
  
  <br />
  @endforeach
</ul>

@endforeach
@endforeach
<P>=============================================================</P>
<h1><strong>DANH SÁCH KẾ HOẠCH - CÔNG VIỆC</strong></h1>
@foreach ($reminders as $reminder)
<span class="box-decoration-clone bg-gradient-to-r from-{{ $reminder['TEN_KE_HOACH'] }}-600 to-{{ $reminder['to'] }} px-4 py-1 rounded shadow mb-2 inline-block">
  <strong>{{ $reminder->TEN_KE_HOACH }}</strong>
</span>
<div class="bg-white/20 backdrop-blur-md border border-white/30 shadow-lg rounded-xl p-6 text-white max-w-7xl mb-8" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7)">

  @foreach ($reminder -> congviecs as $congviec)
  <b>
    {{ $congviec->TEN_CV }}
  </b>
  <b></b>
  <br>
  <div
    class="bg-white/20 backdrop-blur-md border border-white/30 shadow-lg rounded-xl p-6 text-white max-w-7xl mb-8"
    style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7)">
    -- Tiến độ --><br />
    {{ $congviec['TIEN_DO'] }} : <b>{{ $congviec['DO_UU_TIEN'] }}</b>
  </div>
  @endforeach


</div>
@endforeach
@endsection