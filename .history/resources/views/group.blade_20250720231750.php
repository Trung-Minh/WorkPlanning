{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Trưởng nhóm – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
    <head><meta name="csrf-token" content="{{ csrf_token() }}">
    </head>



@endsection

@php($noFooter = true)
