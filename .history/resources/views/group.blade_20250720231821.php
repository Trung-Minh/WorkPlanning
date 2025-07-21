{{-- resources/views/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Trưởng nhóm – WorkPlan')
@php
    $user = Auth::user();
@endphp
@section('content')
    <head><meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <main class=" w-full pt-2 mx-auto mt-5 sm:w-3/4 md:w-5/10 lg:w-5/10 ">
    
    </main>
  

@endsection

@php($noFooter = true)
