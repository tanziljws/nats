@extends('layouts.manage')

@section('title', 'Achievements')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Achievements', 'route' => null],
    ];
@endphp

@section('content')
<div class="text-center mb-8">
    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
        Achievements
    </h1>
    <p class="text-gray-500 mt-2">Manage, edit, publish, and archive all achievements</p>
 </div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.achievements.index') }}" class="flex gap-2 w-full mb-6">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search achievements..."
        autocomplete="off"
        class="flex-grow h-11 px-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3c5e5e]"
    >
    <button type="submit"
        class="h-11 px-5 bg-gradient-to-r from-[#3c5e5e] to-[#425d9e] text-white rounded-xl hover:opacity-90 transition text-sm font-medium shadow">
        <i class="fas fa-search mr-1"></i> Search
    </button>
</form>

<div class="min-h-screen bg-white text-gray-800 px-6 lg:px-10 py-10">

    {{-- Add Button --}}
    <div class="flex justify-end mb-8 gap-4 items-center">
        <form method="GET" action="{{ route('admin.achievements.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <select name="house_id" class="h-11 px-3 border border-gray-300 rounded-xl text-sm" onchange="this.form.submit()">
                <option value="">All Houses</option>
                @foreach(($houses ?? []) as $h)
                    <option value="{{ $h->id }}" {{ (string)($selectedHouseId ?? '') === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.achievements.create', ['house_id' => $selectedHouseId]) }}"
           class="h-11 px-5 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow font-medium hover:opacity-90 transition flex items-center whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> New Achievement
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-green-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Achievement List --}}
    <div class="grid grid-cols-1 gap-6">
        @forelse ($achievements as $item)
        
            <div class="flex flex-col md:flex-row gap-5 bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden p-5 hover:shadow-md transition">

                {{-- Image --}}
                <div class="w-full md:w-44 aspect-[5/3] flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 ring-1 ring-gray-200 shadow-sm hover:shadow-md transition">
                    @if(!empty($item->image) && file_exists(public_path('storage/' . $item->image)))
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover" alt="Achievement Image">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                            <i class="fas fa-trophy text-4xl opacity-40"></i>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex flex-col justify-between flex-grow">
                    <div>
                        <div class="flex justify-between items-start mb-1">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $item->title }}</h2>
                            {{-- House Badge --}}
                                @php
                                    $houseColors = [
                                        'Gryffindor' => 'gradient from-[#5c0c0c] to-[#8a3333]', // merah
                                        'Slytherin' => 'gradient from-[#063015] to-[#336343]',  // hijau
                                        'Ravenclaw' => 'gradient from-[#182552] to-[#6e8ab5]',  // biru
                                        'Hufflepuff' => 'gradient from-[#59510a] to-[#ab8e37]', // kuning
                                    ];

                                    $houseName = $item->house->name ?? null;
                                    $gradient = $houseName && isset($houseColors[$houseName])
                                        ? $houseColors[$houseName]
                                        : 'from-gray-300 to-gray-400';
                                @endphp

                                @if($houseName)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gradient-to-r {{ $gradient }} text-white shadow-sm">
                                        {{ $houseName }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                                        No House
                                    </span>
                                @endif
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($item->description, 250) }}</p>
                    </div>

                    <div class="mt-4 flex flex-col md:flex-row justify-between items-start md:items-end text-sm text-gray-500 gap-3">
                        {{-- Info --}}
                        <div class="flex items-center gap-4">
                            <p class="text-xs text-gray-400">
                                {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '-' }}
                            </p>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12 18 19.5 12 19.5 2.25 12 2.25 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>{{ (int)($item->view_count ?? 0) }}</span>
                            </span>
                            <a href="{{ route('admin.comments.likes-stats') }}" class="inline-flex items-center gap-1 text-red-600 hover:opacity-90 transition text-xs">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                <span>{{ $item->likes()->count() }}</span>
                            </a>
                            <a href="{{ route('admin.comments.achievements', ['achievement_id' => $item->id, 'house_id' => $item->house_id]) }}" class="inline-flex items-center gap-1 text-gray-700 hover:opacity-90 transition text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 10h10M7 14h7" />
                                    <path d="M5 5h14a2 2 0 0 1 2 2v9l-3-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" fill-opacity=".15" />
                                </svg>
                                <span>{{ $item->comments()->count() }}</span>
                            </a>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-3">
                            {{-- Edit --}}
                            <a href="{{ route('admin.achievements.edit', $item->id) }}"
                               class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18" />
                                    <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                    <path d="M14 6l4 4" />
                                    <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                                </svg>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.achievements.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this achievement?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <i class="fas fa-trophy text-4xl opacity-40 mb-2"></i>
                <p>No achievements found.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $achievements->onEachSide(1)->links('vendor.pagination.clean') }}
    </div>
</div>

@endsection

