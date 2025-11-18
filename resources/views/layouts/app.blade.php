<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Hogwarts School')</title>
    <link rel="icon" 
      href="{{ isset($schoolProfile) && $schoolProfile->logo 
          ? asset('storage/' . $schoolProfile->logo) 
          : asset('default-icon.png') }}" 
      type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Tailwind CSS is loaded via Vite in production --}}
    @if(config('app.env') !== 'production')
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    html {
        scroll-behavior: smooth;
    }
    
    [x-cloak] { display: none !important; }
    </style>
    

    
</head>
<body class="bg-gray-50 font-sans flex flex-col min-h-screen">
    
    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Content --}}
    <main class="m-0 p-0 overflow-x-hidden"> 
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')



   <script>

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    const top = target.getBoundingClientRect().top + window.scrollY;
                    window.scrollTo({ top, behavior: 'smooth' });
                }
            });
        });
    </script>
</body>

</html>
