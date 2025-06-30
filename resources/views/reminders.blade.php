{{-- resources/views/nhacnho.blade.php --}}
@extends('layouts.app')

@section('title', 'Nhắc nhở – WorkPlan')

@section('content')
<main class="hidden xl:block">
    <div
      class="relative min-h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('img/snowy-mountains.jpg') }}')"
    >
      <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>

      <div class="relative z-10 text-white p-8">
        <h1 class="text-3xl font-bold mb-6"><strong>Nhắc nhở</strong></h1>

        @foreach([
          ['time'=>'8h30','text'=>'Đi mua rau cho vợ!','from'=>'red','to'=>'red-800'],
          ['time'=>'10h30','text'=>'Đi rước con!','from'=>'indigo','to'=>'red-600'],
          ['time'=>'11h30','text'=>'Gọi họp khẩn','from'=>'indigo','to'=>'red-800'],
          ['time'=>'13h00','text'=>'Đưa con đi học chiều!','from'=>'red-700','to'=>'pink-700'],
          ['time'=>'16h30','text'=>'Đi rước con buổi chiều','from'=>'indigo','to'=>'black'],
        ] as $item)
          <span
            class="box-decoration-clone bg-gradient-to-r from-{{ $item['from'] }}-600 to-{{ $item['to'] }} px-4 py-1 rounded shadow mb-2 inline-block"
          >
            <strong>Deadline</strong>
          </span>
          <div
            class="bg-white/20 backdrop-blur-md border border-white/30 shadow-lg rounded-xl p-6 text-white max-w-7xl mb-8"
            style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7)"
          >
            - Deadline -<br />
            {{ $item['time'] }} : <b>{{ $item['text'] }}</b>
          </div>
        @endforeach
      </div>
    </div>
  </main>

  {{-- Mobile / <XL --}}
  <main class="block xl:hidden">
    <div
      class="relative min-h-screen bg-cover bg-center"
      style="background-image: url('{{ asset('img/snowy-mountains.jpg') }}')"
    >
      <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>

      <div class="relative z-10 text-white p-4 sm:p-8 max-w-5xl mx-auto">
        <h1 class="text-2xl sm:text-3xl font-bold mb-6">Nhắc nhở</h1>

        <div class="space-y-8">
          @foreach([
            ['time'=>'8h30','text'=>'Đi mua rau cho vợ!','from'=>'red','to'=>'red-800'],
            ['time'=>'10h30','text'=>'Đi rước con!','from'=>'indigo','to'=>'red-600'],
            ['time'=>'11h30','text'=>'Gọi họp khẩn','from'=>'indigo','to'=>'red-800'],
            ['time'=>'13h00','text'=>'Đưa con đi học chiều!','from'=>'red-700','to'=>'pink-700'],
            ['time'=>'16h30','text'=>'Đi rước con buổi chiều','from'=>'indigo','to'=>'black'],
          ] as $item)
            <div>
              <span
                class="block w-full text-center bg-gradient-to-r from-{{ $item['from'] }}-600 to-{{ $item['to'] }} text-white font-semibold py-1 rounded-md text-sm sm:text-base"
              >
                Deadline
              </span>
              <div
                class="mt-2 bg-white/20 backdrop-blur-md border border-white/30 shadow-lg rounded-xl p-4 sm:p-6 text-white text-sm sm:text-base"
                style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7)"
              >
                - Deadline -<br />
                {{ $item['time'] }} : <b>{{ $item['text'] }}</b>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>
@endsection