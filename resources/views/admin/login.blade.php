<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Login | Hogwarts Admin Management</title>
    <link rel="icon" 
      href="{{ isset($schoolProfile) && $schoolProfile->logo 
          ? asset('storage/' . $schoolProfile->logo) 
          : asset('default-icon.png') }}" 
      type="image/x-icon">
    
    {{-- Tailwind CSS is loaded via Vite in production --}}
    @if(config('app.env') !== 'production')
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#b03535] via-[#3c5e5e] to-[#425d9e]">

    {{-- Login Card --}}
    <div class="bg-white/95 backdrop-blur-md p-10 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
                Admin Login
            </h1>
            <p class="text-gray-500 mt-2 text-sm">Access the Hogwarts management dashboard</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 rounded-lg p-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block mb-2 font-semibold text-gray-700">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="yourname@hogwarts.edu"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition"
                />
            </div>

            <div>
                <label for="password" class="block mb-2 font-semibold text-gray-700">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    placeholder="••••••••"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition"
                />
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white font-semibold py-3 rounded-xl shadow hover:opacity-90 transition duration-200 flex items-center justify-center gap-2"
            >
                <i class="fas fa-sign-in-alt"></i>
                Login
            </button>
        </form>

        {{-- Footer --}}
        <p class="text-center text-gray-500 text-sm mt-8">
            &copy; {{ date('Y') }} Hogwarts Admin Portal
        </p>
    </div>

</body>
</html>
