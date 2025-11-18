<aside class="w-64 h-screen fixed top-0 left-0 z-50 flex flex-col bg-gradient-to-b from-[#b03535] via-[#3c5e5e] to-[#425d9e] shadow-2xl text-white">
    {{-- Header --}}
    <div class="px-6 py-5 border-b border-white/20">
        <h1 class="text-2xl font-bold tracking-wide drop-shadow-md">Hogwarts Admin</h1>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @php
            $links = [
                ['url' => '/admin', 'label' => 'Dashboard'],
                ['url' => '/admin/hogwarts-prophet', 'label' => 'Hogwarts Prophet'],
                ['url' => '/admin/achievements', 'label' => 'Achievements'],
                ['url' => '/admin/facilities', 'label' => 'Facility Categories'],

                // Houses (main)
                ['url' => '/admin/houses', 'label' => 'Houses'],

                // --- NEW SUBMENUS ---
                ['url' => route('admin.professors.index'), 'label' => '— Professors', 'submenu' => true],
                ['url' => route('admin.students.index'), 'label' => '— Students', 'submenu' => true],

                // Rest
                ['url' => route('admin.school-profile.index'), 'label' => 'School Profile'],
                ['url' => route('admin.comments.index'), 'label' => 'Comments & Likes'],
                ['url' => route('admin.users.index'), 'label' => 'User Management'],
            ];
        @endphp

        @foreach ($links as $link)
            @php
                $isActive = request()->is(ltrim(parse_url($link['url'], PHP_URL_PATH), '/'));
            @endphp

            <a href="{{ $link['url'] }}"
                class="block px-3 py-2 rounded-lg transition duration-200
                       {{ $isActive ? 'bg-white/20 font-semibold backdrop-blur-sm' : 'hover:bg-white/10' }}
                       {{ isset($link['submenu']) ? 'ml-4 text-sm opacity-90' : '' }}">
                {{ $link['label'] }}
            </a>
        @endforeach

        <a href="/" class="block px-3 py-2 rounded-lg hover:bg-white/10 transition duration-200">
            Back to Site
        </a>
    </nav>

    {{-- Footer --}}
    <div class="px-6 py-4 border-t border-white/20">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center px-3 py-2 rounded-lg bg-red/30 hover:bg-white text-white-400 hover:text-red-400 transition font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
                </svg>
                Logout
            </button>
        </form>

        <p class="mt-4 text-xs text-center text-white/70">© {{ date('Y') }} Hogwarts Admin</p>
    </div>
</aside>
