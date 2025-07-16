{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'WorkPlan')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @vite(['resources/js/plans.js'])
</head>

<body class="flex flex-col min-h-screen bg-gray-100">

  @include('partials.header')

  <main class="flex-1 w-full px-4 py-4 mx-auto">
    @yield('content')
  </main>

  @if(empty($noFooter))
    @include('partials.footer')
  @endif

  @stack('scripts')

</body>

</html>
