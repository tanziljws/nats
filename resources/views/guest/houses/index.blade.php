@extends('layouts.app')

@section('content')
{{-- HERO SECTION --}}
<div class="bg-[#f4f1ec] min-h-screen">
<section id="hero" class="relative w-full h-screen flex items-center justify-center overflow-hidden">

    {{-- Gradient Background --}}
    <div class="absolute inset-0 w-full h-full">
        <div class="w-full h-full"
             style="background: linear-gradient(90deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);">
        </div>
    </div>

    {{-- Optional overlay --}}
    <div class="absolute inset-0 bg-black/30"></div>

    {{-- Content --}}
    <div class="relative z-10 text-center flex flex-col items-center justify-center gap-6 px-6">
        <img src="{{ $schoolProfile && $schoolProfile->logo ? asset('storage/' . $schoolProfile->logo) : asset('images/hogwarts.jpg') }}"
             alt="Hogwarts Logo"
             class="w-28 h-28 md:w-36 md:h-36 drop-shadow-lg">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif font-bold text-white leading-tight">
            Welcome to Hogwarts
        </h1>
        <p class="text-md sm:text-lg md:text-xl text-gray-200 max-w-2xl">
            Discover the houses, achievements, and stories of our students throughout the centuries
        </p>
        <div class="mt-6 flex flex-col sm:flex-row gap-4">
            <a href="#houses"
               class="px-6 py-3 border border-white text-white font-semibold rounded-full shadow-lg hover:bg-white hover:text-black transition">
                Explore Houses
            </a>
        </div>
    </div>
</section>

{{-- HOUSE SECTIONS --}}
@foreach($houseStats as $house)
<section id="houses" class="relative w-full flex flex-col justify-center items-center bg-[#f4f1ec] overflow-hidden min-h-screen pt-20">

    {{-- House Info --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 md:px-20 flex flex-col md:flex-row items-center justify-center gap-12">

        {{-- Logo --}}
        <div class="flex-shrink-0 flex justify-center items-center">
            <img src="{{ asset('storage/' . $house->logo) }}" class="w-44 h-44 md:w-60 md:h-60 drop-shadow-lg">
        </div>

        {{-- Text --}}
        <div class="flex-1 flex flex-col justify-center text-center md:text-left">
            <h2 class="text-4xl sm:text-5xl font-serif font-bold mb-2 md:mb-4">{{ $house->name }}</h2>

            @if(!empty($house->characteristics))
                <p class="text-xl sm:text-2xl font-serif font-semibold mb-2 md:mb-4">
                    Student Characteristic: <span class="underline">{{ implode(', ', $house->characteristics) }}</span>
                </p>
            @endif

            {{-- Divider --}}
            <div class="w-24 h-1.5 mb-2 md:mb-4">
                <svg width="100%" height="100%" viewBox="0 0 96 4" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradient-{{ $house->id }}" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#b03535" />
                            <stop offset="50%" style="stop-color:#3c5e5e" />
                            <stop offset="100%" style="stop-color:#425d9e" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#gradient-{{ $house->id }})" rx="2" ry="2" />
                </svg>
            </div>

            <p class="text-md font-serif max-w-xl mx-auto md:mx-0">{{ $house->description }}</p>
        </div>
    </div>

    {{-- Achievements + Stats --}}
    <div class="relative w-full flex flex-col md:flex-row items-center justify-center gap-8 mt-12 max-w-7xl mx-auto px-4">

        {{-- Stats (mobile top, center) --}}
        <div class="flex-1 flex flex-col justify-center items-center bg-white/10 p-6 rounded-xl text-black mb-6 md:mb-0 text-center order-1 md:order-2">
            <div class="space-y-4 sm:space-y-6">
                <div>
                    <span class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold">+{{ number_format($house->total_alumni) }}</span>
                    <p class="text-sm sm:text-md md:text-lg">Alumnus over past centuries</p>
                </div>
                <div>
                    <span class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold">{{ $house->students_last7years }}</span>
                    <p class="text-sm sm:text-md md:text-lg">Active Students (Last 7 Years)</p>
                </div>
                <div>
                    <span class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold">{{ $house->professors_count }}</span>
                    <p class="text-sm sm:text-md md:text-lg">Certified Professors</p>
                </div>
            </div>
        </div>

        {{-- Achievements Grid --}}
        <div class="flex-[2] flex flex-col gap-6 w-full order-2 md:order-1">
            <h3 class="text-2xl sm:text-3xl font-serif font-semibold mb-4 text-center md:text-left pl-2">
                <span class="w-1.5 h-6 rounded-full inline-block mr-2"
                      style="background: linear-gradient(180deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);">
                </span> Achievements
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($house->achievements as $achievement)
                    @php
                        $houseColors = [
                            'Gryffindor' => ['from' => '#5c0c0c', 'to' => '#8a3333'],
                            'Slytherin' => ['from' => '#063015', 'to' => '#336343'],
                            'Ravenclaw' => ['from' => '#182552', 'to' => '#6e8ab5'],
                            'Hufflepuff' => ['from' => '#59510a', 'to' => '#ab8e37'],
                        ];
                        $houseName = $house->name;
                        $gradientFrom = $houseColors[$houseName]['from'] ?? '#888';
                        $gradientTo = $houseColors[$houseName]['to'] ?? '#aaa';
                    @endphp

                    <a href="{{ route('guest.achievements.index') }}?modal={{ $achievement->id }}" class="block group cursor-pointer w-full">
                        <article class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col w-full">
                            <div class="relative h-48 overflow-hidden rounded-t-2xl">
                                <img src="{{ $achievement->image ? asset('storage/' . $achievement->image) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $achievement->title }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">

                                <span class="absolute top-3 left-3 px-2 py-1 text-xs font-semibold rounded-full text-white shadow-sm z-10"
                                      style="background: linear-gradient(90deg, {{ $gradientFrom }} 0%, {{ $gradientTo }} 100%);">
                                    {{ $houseName }}
                                </span>

                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <div class="flex-1">
                                    <div class="text-sm text-gray-500 mb-2">
                                        {{ \Carbon\Carbon::parse($achievement->date)->format('F j, Y') }}
                                    </div>
                                    <h3 class="text-lg font-serif font-semibold text-gray-900 mb-2 line-clamp-2">
                                        {{ $achievement->title }}
                                    </h3>
                                    <p class="text-gray-600 text-sm line-clamp-3">
                                        {{ Str::limit($achievement->description, 50) }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
<div class="w-full border-t border-gray-300 my-6"></div>

@endforeach
</div>
@endsection
