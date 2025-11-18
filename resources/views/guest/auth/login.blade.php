<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Login | Hogwarts CMS</title>
    {{-- Tailwind CSS is loaded via Vite in production --}}
    @if(config('app.env') !== 'production')
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#b03535] via-[#3c5e5e] to-[#425d9e] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
        {{-- Header --}}
        <div class="text-center">
            <h2 class="text-3xl font-extrabold font-serif bg-clip-text text-black drop-shadow">
                Welcome Back!
            </h2>
            <p class="mt-2 text-sm text-gray-600 italic">Sign in to your Hogwarts account</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Login Form --}}
        <form class="mt-8 space-y-6" action="{{ route('user.login.submit') }}" method="POST">
            @if(request('redirect'))
                <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif
            @csrf
            
            <div class="space-y-4">
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email Address
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           required 
                           value="{{ old('email') }}"
                           class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition"
                           placeholder="your.email@hogwarts.edu">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           required 
                           class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition"
                           placeholder="Enter your password">
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 text-[#425d9e] focus:ring-[#425d9e] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>
            </div>

            {{-- Submit Button --}}
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#425d9e] transition">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-white/70"></i>
                    </span>
                    Sign In
                </button>
            </div>

            {{-- Register Link --}}
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('user.register') }}" class="font-medium text-[#425d9e] hover:text-[#3c5e5e] transition">
                        Register here
                    </a>
                </p>
            </div>

            {{-- OR divider --}}
            <div class="flex items-center justify-center my-6">
                <div class="w-1/5 border-t border-gray-300"></div>
                <span class="mx-3 text-gray-500 text-sm">or</span>
                <div class="w-1/5 border-t border-gray-300"></div>
            </div>

            {{-- Continue as Guest --}}
            <div class="text-center">
                <a href="{{ route('guest.home') }}"
                   class="inline-flex items-center justify-center w-full py-3 px-4 text-sm font-medium rounded-lg
                          border border-[#3c5e5e] text-[#3c5e5e] hover:bg-[#3c5e5e] hover:text-white 
                          transition-all duration-300 shadow-sm">
                    <i class="fas fa-hat-wizard mr-2"></i>
                    Continue as Guest
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
