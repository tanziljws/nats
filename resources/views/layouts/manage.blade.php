<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hogwarts School')</title>
    <link rel="icon" 
      href="{{ isset($schoolProfile) && $schoolProfile->logo 
          ? asset('storage/' . $schoolProfile->logo) 
          : asset('default-icon.png') }}" 
      type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CSS is loaded via Vite in production --}}
    @if(config('app.env') !== 'production')
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    {{-- Font Awesome --}}
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-papCNhMlgNw1JfMT7yD9xkAcqZexOt+LsFbV+CGWhcX8Z5G0zDBi5wD2R53gT+5VCNsHk9WjoxbU9nU5ZK7eMA==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-800">

    {{-- Sidebar --}}
    @include('partials.sidebar')
    

    {{-- Main content --}}
    <main class="pl-64 pt-6">
        <div class="container mx-auto px-6">

            {{-- Back button --}}
            @yield('back-button')

            {{-- Breadcrumb --}}
            @include('partials.breadcrumb')

            {{-- Page content --}}
            @yield('content')
            
        </div>
    </main>

    {{-- Scripts --}}
    @stack('scripts')

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
