{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'WorkPlan')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

  @include('partials.header')

  <main class="flex-1 w-full mx-auto px-4 py-6">
    @yield('content')
  </main>

  @if(empty($noFooter))
    @include('partials.footer')
  @endif
</body>

</html>