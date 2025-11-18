<section id="fasilitas" class="py-20 bg-gradient-to-b from-[#fdfcf9] via-[#f4f1ec] to-[#f0ede7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-serif font-extrabold text-gray-900 tracking-tight">School Facilities</h2>

            <div class="w-24 h-1 mx-auto mt-4">
                <svg width="100%" height="100%" viewBox="0 0 96 4" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#b03535" />
                            <stop offset="50%" style="stop-color:#3c5e5e" />
                            <stop offset="100%" style="stop-color:#425d9e" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#gradient)" rx="2" ry="2" />
                </svg>
            </div>

            <p class="mt-6 text-lg text-gray-700  mx-auto leading-relaxed">
                From enchanted classrooms to cozy common rooms, Hogwarts supports every spell of your education.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($categories as $category)
                @php
                    $bgImage = $category->coverPhoto?->image 
                        ? asset('storage/' . $category->coverPhoto->image) 
                        : asset('images/placeholder.jpg');
                @endphp

                <a href="{{ route('guest.facilities.index', $category->slug) }}" 
                   class="relative group rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition transform hover:-translate-y-1">

                    {{-- Image --}}
                    <div class="h-64 bg-cover bg-center rounded-2xl transition-transform duration-500 group-hover:scale-105" 
                         style="background-image: url('{{ $bgImage }}');"></div>

                    {{-- Dark Overlay --}}
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition rounded-2xl"></div>

                    {{-- Text --}}
                    <div class="absolute inset-0 flex items-center justify-center p-6">
                        <h3 class="text-2xl font-serif text-white text-center drop-shadow-md">
                            {{ $category->name }}
                        </h3>
                    </div>
                </a>
            @empty
                <p class="text-center text-gray-400 col-span-full">Nothing</p>
            @endforelse
        </div>

        {{-- View More Button --}}
        <div class="mt-12 text-center">
            <a href="{{ route('guest.facilities.index') }}"
                class="relative overflow-hidden group inline-block px-8 py-3 font-serif rounded-full
                        text-white tracking-wide shadow-md hover:shadow-lg transition-all duration-300
                        hover:scale-105"
                style="
                    background: linear-gradient(90deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);
                    background-clip: padding-box;">
                    <span class="relative z-10">View All Facilities</span>
                
                </a>
        </div>
    </div>
</section>
