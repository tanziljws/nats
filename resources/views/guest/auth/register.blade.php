{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Register | Hogwarts CMS</title>
    {{-- Tailwind CSS is loaded via Vite in production --}}
    @if(config('app.env') !== 'production')
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#425d9e] via-[#3c5e5e] to-[#b03535] py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Magical aura background --}}
    <div class="absolute inset-0 opacity-30 blur-3xl bg-[radial-gradient(circle_at_center,#ffffff20_0%,transparent_70%)]"></div>

    <div class="relative max-w-md w-full bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-8 space-y-8 z-10 animate-fade-in">
        {{-- Header --}}
        <div class="text-center">
            <h2 class="text-3xl font-extrabold font-serif bg-clip-text text-black drop-shadow">
                Join Hogwarts!
            </h2>
            <p class="mt-2 text-sm text-gray-600 italic">Create your account to get started</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                    <div class="text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Register Form --}}
        <form class="space-y-6" action="{{ route('user.register.submit') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition placeholder-gray-400"
                        placeholder="Full Name">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition placeholder-gray-400"
                        placeholder="email@hogwarts.com">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition placeholder-gray-400"
                        placeholder="Minimum 8 characters">
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#425d9e] focus:border-transparent transition placeholder-gray-400"
                        placeholder="Re-enter your password">
                </div>
            </div>

            {{-- Submit Button --}}
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center items-center py-3 px-4 border border-transparent 
                           text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] 
                           hover:brightness-110 focus:ring-4 focus:ring-[#425d9e]/40 transition-all shadow-md">
                    <i class="fas fa-user-plus text-white/80 mr-2"></i>
                    Create Account
                </button>
            </div>

            {{-- Login Link --}}
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('user.login') }}" class="font-medium text-[#425d9e] hover:text-[#3c5e5e] transition">
                        Sign in here
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

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
    </style>
</body>
</html>
