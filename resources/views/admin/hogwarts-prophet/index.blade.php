@extends('layouts.manage')

@section('title', 'Hogwarts Prophet')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Hogwarts Prophet', 'route' => null],
    ];
@endphp

@section('content')
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
            Hogwarts Prophet
        </h1>
        <p class="text-gray-500 mt-2">Manage, edit, publish, and archive all school news and updates</p>
    </div>

        <form method="GET" action="{{ route('admin.hogwarts-prophet.index') }}" class="flex gap-2 w-full mb-6">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by title or writer..."
                autocomplete="off"
                class="flex-grow h-11 px-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3c5e5e]"
            >
            <button type="submit" 
                    class="h-11 px-5 bg-gradient-to-r from-[#3c5e5e] to-[#425d9e] text-white rounded-xl hover:opacity-90 transition text-sm font-medium shadow">
                <i class="fas fa-search mr-1"></i> Search
            </button>
        </form>

<div class="min-h-screen bg-white text-gray-800 px-6 lg:px-10 py-10">
    {{-- Search & Add --}}
    <div class="flex justify-end mb-8 gap-4">
        <a href="{{ route('admin.hogwarts-prophet.create') }}"
           class="h-11 px-5 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow font-medium hover:opacity-90 transition flex items-center whitespace-nowrap">
            <i class="fas fa-feather-alt mr-2"></i> New Article
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-green-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Articles List --}}
    <div class="grid grid-cols-1 gap-6">
        @forelse($news as $item)
            <div class="flex flex-col md:flex-row gap-5 bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden p-5 hover:shadow-md transition">
                {{-- Image --}}
                <div class="w-full md:w-44 aspect-[5/3] flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 ring-1 ring-gray-200 shadow-sm hover:shadow-md transition">
                    @if(!empty($item->image) && file_exists(public_path('storage/' . $item->image)))
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover" alt="Thumbnail">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                            <i class="fas fa-scroll text-4xl opacity-40"></i>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex flex-col justify-between flex-grow">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $item->title }}</h2>
                            {{-- Status Badge --}}
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $item->is_public ? 'Public' : 'Private' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($item->content, 250) }}</p>
                    </div>

                    <div class="mt-4 flex flex-col md:flex-row justify-between items-start md:items-end text-sm text-gray-500 gap-3">
                        {{-- Info --}}
                        <div class="space-y-1">
                            <p><span class="font-medium">{{ $item->writer }}</span></p>
                            <p class="text-xs text-gray-400">{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : 'Unknown Date' }} </p>
                            <div class="flex items-center gap-4 mt-1 text-xs">
                                <span class="inline-flex items-center gap-1 text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M21 12q-3.6 6-9 6t-9-6q3.6-6 9-6t9 6" />
                                        </g>
                                    </svg> {{ $item->view_count ?? 0 }}
                                </span>
                                <a href="{{ route('admin.comments.likes-stats') }}" class="inline-flex items-center gap-1 text-red-600 hover:opacity-90 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037.033l.034-.03a6 6 0 0 1 4.733-1.44l.246.036a6 6 0 0 1 3.364 10.008l-.18.185l-.048.041l-7.45 7.379a1 1 0 0 1-1.313.082l-.094-.082l-7.493-7.422A6 6 0 0 1 6.979 3.074" />
                                    </svg> {{ $item->likes_count ?? 0 }}
                                </a>
                                <a href="{{ route('admin.comments.hogwarts-prophet', ['article_id' => $item->id]) }}" class="inline-flex items-center gap-1 text-gray-700 hover:opacity-90 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M7 10h10M7 14h7" />
                                        <path fill="currentColor" d="M5 5h14a2 2 0 0 1 2 2v9l-3-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" fill-opacity="0.15" />
                                    </svg> {{ $item->comments()->count() }}
                                </a>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-3">
                            {{-- Edit --}}
                            <a href="{{ route('admin.hogwarts-prophet.edit', $item->id) }}" 
                            class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18" />
                                <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                <path d="M14 6l4 4" />
                                <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                             </svg>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.hogwarts-prophet.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h5c0.5 0 1 -0.5 1 -1v-14M12 20h-5c-0.5 0 -1 -0.5 -1 -1v-14" />
                                                    <path d="M4 5h16" />
                                                    <path d="M10 4h4M10 9v7M14 9v7" />
                                                </svg>
                                    </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-600 py-10">
                <i class="fas fa-feather text-4xl opacity-40 mb-2"></i>
                <p>No articles found.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-center">
        {{ $news->onEachSide(1)->links('vendor.pagination.clean') }}
    </div>
</div>
@endsection
