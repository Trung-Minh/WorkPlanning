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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.invite-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Ngăn reload

                    const button = this.querySelector('button');
                    const id_user = this.dataset.user;
                    const id_nhom = this.dataset.nhom;

                    fetch('/invite', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ id_user, id_nhom })
                    })
                    .then(res => {
                        if (res.ok) {
                            button.disabled = true;
                            button.classList.add('opacity-50');
                            button.innerText = 'Đã mời';
                        } else {
                            alert('Lỗi khi gửi lời mời!');
                        }
                    })
                    .catch(err => {
                        alert('Lỗi mạng!');
                        console.error(err);
                    });
                });
            });
        });
    </script>

@endsection

@php($noFooter = true)
