@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Các công việc của bạn</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID Công Việc</th>
                <th>Tên Công Việc</th>
                <th>Tiến Độ</th>
                <th>Mức Công Việc</th>
                <th>Kế Hoạch</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->ID_CV }}</td>
                <td>{{ $task->TEN_CV }}</td>
                <td>{{ $task->TIEN_DO }}%</td>
                <td>
                    @foreach($task->subTasks as $subTask)
                        <div>{{ $subTask->TEN_MUC }} ({{ $subTask->THOI_HAN_HOAN_THANH }})</div>
                    @endforeach
                </td>
                <td>{{ $task->plan->TEN_KE_HOACH }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection