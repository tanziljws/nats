@extends('admin.layout')

@section('content')
<div class="min-h-screen bg-white text-gray-800 px-6 py-10">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
            Likes Statistics
        </h1>
        <p class="text-gray-500 mt-2">Overview of most liked content across the Hogwarts network</p>
    </div>

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-10">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-800 transition">Dashboard</a> /
        <a href="{{ route('admin.comments.index') }}" class="hover:text-gray-800 transition">Comments</a> /
        <span class="text-gray-400">Likes Statistics</span>
    </nav>

    {{-- ============ Facility Photos ============ --}}
    <section class="mb-12 px-6 lg:px-10">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#b03535] via-[#3c5e5e] to-[#425d9e] rounded-full"></span>
            Top 10 Liked Facility Photos
        </h2>

        <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Photo Name</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Likes</th>
                        <th class="py-3 px-4">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topFacilityPhotos as $index => $photo)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $photo->name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-lg bg-gray-200 text-gray-700 text-xs">
                                    {{ $photo->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-red-600 font-semibold"><i class="fas fa-heart"></i> {{ $photo->likes_count }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-blue-600 font-semibold"><i class="fas fa-comment"></i> {{ $photo->comments->count() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ============ Hogwarts Prophet Articles ============ --}}
    <section class="mb-12 px-6 lg:px-10">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
            Top 10 Liked Hogwarts Prophet Articles
        </h2>

        <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Article Title</th>
                        <th class="py-3 px-4">Writer</th>
                        <th class="py-3 px-4">Likes</th>
                        <th class="py-3 px-4">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topArticles as $index => $article)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ Str::limit($article->title, 60) }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $article->writer }}</td>
                            <td class="py-3 px-4">
                                <span class="text-red-600 font-semibold"><i class="fas fa-heart"></i> {{ $article->likes_count }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-blue-600 font-semibold"><i class="fas fa-comment"></i> {{ $article->comments->count() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ============ Achievements ============ --}}
    <section class="mb-12 px-6 lg:px-10">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#3c5e5e] via-[#425d9e] to-[#b03535] rounded-full"></span>
            Top 10 Liked Achievements
        </h2>

        <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Achievement Title</th>
                        <th class="py-3 px-4">House</th>
                        <th class="py-3 px-4">Likes</th>
                        <th class="py-3 px-4">Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topAchievements as $index => $achievement)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ Str::limit($achievement->title, 60) }}</td>
                            <td class="py-3 px-4">
                                @php
                                    $houseName = $achievement->house->name ?? null;
                                    $houseGradients = [
                                        'Gryffindor' => ['#5c0c0c', '#8a3333'],
                                        'Slytherin' => ['#063015', '#336343'],
                                        'Ravenclaw' => ['#182552', '#6e8ab5'],
                                        'Hufflepuff' => ['#59510a', '#ab8e37'],
                                    ];
                                    $from = $houseGradients[$houseName][0] ?? '#888888';
                                    $to   = $houseGradients[$houseName][1] ?? '#aaaaaa';
                                @endphp
                                @if($houseName)
                                    <span class="inline-flex items-center justify-center h-6 w-20 text-white text-xs font-semibold rounded-full shadow-sm"
                                          style="background: linear-gradient(90deg, {{ $from }} 0%, {{ $to }} 100%);">
                                        {{ $houseName }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full bg-gray-200 text-gray-700 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-red-600 font-semibold"><i class="fas fa-heart"></i> {{ $achievement->likes_count }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-blue-600 font-semibold"><i class="fas fa-comment"></i> {{ $achievement->comments->count() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
