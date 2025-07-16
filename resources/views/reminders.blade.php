{{-- resources/views/nhacnho.blade.php --}}
@extends('layouts.app')

@section('title', 'Nhắc nhở – WorkPlan')

@section('content')
<h1>Danh sách nhắc nhở</h1>

@if ($reminders->isEmpty())
<p>Chưa có nhắc nhở nào.</p>
@else
<table border="1" cellpadding="10" cellspacing="0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Thời gian nhắc</th>
      <th>Nội dung</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($reminders as $reminder)
    <tr>
      <td>{{ $reminder->ID_CAUHINH }}</td>
      <td>{{ \Carbon\Carbon::parse($reminder->THOI_GIAN_TRUOC_HAN)->format('d/m/Y H:i') }}</td>
      <td>{{ $reminder->NOI_DUNG_TB }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endif

@include('tasks.index')

@endsection