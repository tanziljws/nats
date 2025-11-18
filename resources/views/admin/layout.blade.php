<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hogwarts Admin Panel</title>

    {{-- Tailwind CSS is loaded via Vite in production --}}
    @if(config('app.env') !== 'production')
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-papCNhMlgNw1JfMT7yD9xkAcqZexOt+LsFbV+CGWhcX8Z5G0zDBi5wD2R53gT+5VCNsHk9WjoxbU9nU5ZK7eMA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body class="min-h-screen flex flex-col bg-gray-50 text-gray-800">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <main class="flex-grow pl-64 pt-6">
        @yield('content')
    </main>

</body>
</html>
