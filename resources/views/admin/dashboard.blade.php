@extends('layouts.manage')

@section('title', 'Dashboard')

@section('content')
<section class="min-h-screen bg-white text-gray-800 pt-20 pb-10 px-6">
    <div class="max-w-7xl mx-auto space-y-10">

        {{-- Centered Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
                Admin Dashboard
            </h1>
            <p class="text-gray-500 mt-2">Welcome, {{ $admin->formal_name ?? 'Admin' }} — Manage Hogwarts from one place</p>
        </div>

        {{-- School Overview --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">School Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <p class="text-lg font-semibold">{{ $school->title ?? 'School Name Not Set' }}</p>
                    <p class="text-gray-600">{{ $school->address ?? 'Address Not Set' }}</p>
                    <p class="text-gray-600">{{ $school->phone ?? 'Phone Not Set' }}</p>
                    <p class="text-gray-600">{{ $school->email ?? 'Email Not Set' }}</p>
                </div>
                <div class="flex justify-start md:justify-end">
                    <a href="{{ route('admin.school-profile.edit') }}" 
                       class="px-5 py-2.5 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl font-medium shadow hover:opacity-90 transition">
                       Manage School Profile
                    </a>
                </div>
            </div>
        </div>

        {{-- Students Overview --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Students Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                {{-- Total Students + House Stats --}}
                <div class="col-span-1 flex flex-col h-full">
                    <p id="totalStudents"
                       class="text-5xl font-bold mb-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
                        0
                    </p>
                    <p class="text-gray-600 mb-4">Active Students (last 7 years)</p>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        @foreach($houseStats as $house)
                            <a href="{{ route('admin.houses.edit', $house->id) }}"
                            class="p-4 rounded-xl border border-gray-200 bg-gray-50 shadow-sm text-center 
                                    transform transition duration-300 hover:scale-105 hover:shadow-lg
                                    block cursor-pointer">

                                <img src="{{ asset('storage/' . $house->logo) }}" 
                                    class="w-10 h-10 mx-auto mb-2" 
                                    alt="{{ $house->name }}">

                                <p class="font-semibold text-gray-800">{{ $house->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $house->students_last7years }} students</p>
                            </a>
                        @endforeach
                    </div>

                </div>

                {{-- Chart --}}
                <div class="col-span-2 flex items-center">
                    <canvas id="studentChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>

        {{-- Latest Hogwarts Prophet & Achievements --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Hogwarts Prophet --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Latest Hogwarts Prophet</h2>
                <div class="flex flex-col space-y-3">
                    @foreach($latestNews as $news)
                        <div class="flex gap-3 bg-gray-50 rounded-lg overflow-hidden border border-gray-200 relative">
                            <div class="absolute px-2 py-1 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white text-xs font-semibold rounded-br-lg rounded-tr-lg">
                                {{ $news->category ?? 'General' }}
                            </div>
                            <div class="w-28 h-20 overflow-hidden bg-gray-200 rounded-l-lg">
                                @if($news->image && file_exists(public_path('storage/' . $news->image)))
                                    <img src="{{ asset('storage/' . $news->image) }}" class="w-full h-full object-cover" alt="{{ $news->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-scroll"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2 flex flex-col justify-center">
                                <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $news->title }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ Str::limit($news->content, 50) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.hogwarts-prophet.index') }}" 
                   class="mt-4 inline-block px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-lg text-sm shadow hover:opacity-90 transition">
                   Manage Hogwarts Prophet
                </a>
            </div>

            {{-- Achievements --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Latest Achievements</h2>
                <div class="flex flex-col space-y-3">
                    @foreach($latestAchievements as $achievement)
                        <div class="flex gap-3 bg-gray-50 rounded-lg overflow-hidden border border-gray-200 relative">
                            <div class="absolute px-2 py-1 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white text-xs font-semibold rounded-br-lg rounded-tr-lg">
                                {{ $achievement->category ?? 'Achievement' }}
                            </div>
                            <div class="w-28 h-20 overflow-hidden bg-gray-200 rounded-l-lg">
                                @if($achievement->image && file_exists(public_path('storage/' . $achievement->image)))
                                    <img src="{{ asset('storage/' . $achievement->image) }}" class="w-full h-full object-cover" alt="{{ $achievement->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2 flex flex-col justify-center">
                                <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $achievement->title }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ Str::limit($achievement->description, 50) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.achievements.index') }}" 
                   class="mt-4 inline-block px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-lg text-sm shadow hover:opacity-90 transition">
                   Manage Achievements
                </a>
            </div>
        </div>

        {{-- View Statistics --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8">
           <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
            View Statistics
        </h2>
        <p class="text-gray-500 mt-2">Monitor and analyze engagement on facility photos</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Views -->
        <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-[#425d9e] uppercase mb-1">Total Views</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalViews) }}</h3>
                </div>
                <div class="w-12 h-12 bg-[#425d9e] rounded-full flex items-center justify-center">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500">Across all uploaded facility photos</p>
        </div>

        <!-- Total Photos -->
        <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-[#3c5e5e] uppercase mb-1">Total Photos</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalPhotos) }}</h3>
                </div>
                <div class="w-12 h-12 bg-[#3c5e5e] rounded-full flex items-center justify-center">
                    <i class="fas fa-images text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500">Uploaded facility images</p>
        </div>

        <!-- Average Views per Photo -->
        <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold text-[#b03535] uppercase mb-1">Avg Views/Photo</p>
                    <h3 class="text-3xl font-bold text-gray-900">
                        {{ $totalPhotos > 0 ? number_format($totalViews / $totalPhotos, 1) : 0 }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-[#b03535] rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500">Average engagement per photo</p>
        </div>
    </div>

    {{-- Most Viewed Photos --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-fire mr-2 text-[#b03535]"></i> Most Viewed Photos
        </h3>

        <div class="space-y-3">
            @forelse($mostViewedPhotos as $index => $photo)
                <div class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 hover:shadow transition">
                    <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full flex items-center justify-center text-white font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                        @if($photo->image)
                            <img src="{{ asset('storage/' . $photo->image) }}" alt="{{ $photo->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $photo->name }}</p>
                        <p class="text-sm text-gray-500">{{ $photo->category->name ?? 'No Category' }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-[#425d9e]">
                        <i class="fas fa-eye"></i>
                        <span class="font-bold">{{ number_format($photo->view_count) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No photos yet</p>
            @endforelse
        </div>
    </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.comments.facility-photos') }}" 
                   class="flex items-center justify-center px-4 py-3 border-2 border-[#425d9e] text-[#425d9e] rounded-xl hover:bg-[#f0f4ff] transition">
                    <i class="fas fa-images mr-2"></i> Facility Comments
                </a>
                <a href="{{ route('admin.comments.hogwarts-prophet') }}" 
                   class="flex items-center justify-center px-4 py-3 border-2 border-[#059669] text-[#059669] rounded-xl hover:bg-[#ecfdf5] transition">
                    <i class="fas fa-newspaper mr-2"></i> Prophet Comments
                </a>
                <a href="{{ route('admin.comments.achievements') }}" 
                   class="flex items-center justify-center px-4 py-3 border-2 border-[#7c3aed] text-[#7c3aed] rounded-xl hover:bg-[#faf5ff] transition">
                    <i class="fas fa-trophy mr-2"></i> Achievement Comments
                </a>
                <a href="{{ route('admin.comments.likes-stats') }}" 
                   class="flex items-center justify-center px-4 py-3 border-2 border-[#b03535] text-[#b03535] rounded-xl hover:bg-[#fef2f2] transition">
                    <i class="fas fa-heart mr-2"></i> Likes Statistics
                </a>
            </div>
        </div>

    </div>
</section>

{{-- Chart & Counter Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('totalStudents');
    const target = {{ $studentsTotal }};
    let count = 0;
    const duration = 1000;
    const stepTime = Math.max(Math.floor(duration / target), 10);
    const counter = setInterval(() => {
        count += 1;
        el.textContent = count;
        if (count >= target) clearInterval(counter);
    }, stepTime);

    const ctx = document.getElementById('studentChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($studentPerYear['years']) !!},
            datasets: [{
                label: 'Total Students',
                data: {!! json_encode($studentPerYear['totals']) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection
