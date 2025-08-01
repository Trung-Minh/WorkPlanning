<!DOCTYPE html>
<html lang="vi">

<head>
   <script>
    (function () {
        try {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            console.error('Dark mode script error:', e);
        }
    })();
</script>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('newlogo.ico') }}" type="image/x-icon">

  <title>@yield('title', 'WorkPlanning')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/plans.js', 'resources/js/header.js'])
</head>

<body class="flex flex-col min-h-screen bg-gray-100">
  @include('partials.header')


   <main class="m flex-1 w-full px-4 py-4 mx-auto">
    @yield('content')
  </main> 
  
  
</body>

</html>
