{{-- resources/views/admin/facilities/index.blade.php --}}
@extends('layouts.manage')

@section('title', 'Facilities')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Facilities', 'route' => null],
    ];
@endphp

@section('content')
<div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
            Facilities Management
        </h1>
        <p class="text-gray-500 mt-2">Manage facility categories and photos in the Hogwarts network</p> 
    </div>
<div class="min-h-screen bg-white text-gray-800 px-6 py-10">
    {{-- Header --}}
    
    
    {{-- ========== CATEGORY SECTION ========== --}}
    <section class="mb-16 px-6 lg:px-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <span class="w-1.5 h-8 bg-gradient-to-b from-[#b03535] via-[#3c5e5e] to-[#425d9e] rounded-full"></span>
                Facility Categories
            </h2>
            <a href="{{ route('admin.facilities.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                + Add Category
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Category Name</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-3 px-4 text-gray-500">{{ $category->id }}</td>
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $category->name }}</td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('admin.facilities.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">No categories available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ========== FACILITIES GALLERY SECTION ========== --}}
    <section class="px-6 lg:px-10">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
            Facilities Gallery
        </h2>

        
        @foreach($categories as $category)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $category->name }}</h3>
                    <a href="{{ route('admin.facilities.categories.photos.create', $category->id) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                        + Add Photo
                    </a>
                </div>
                <div class="pb-4 border-b border-gray-300"></div>

                @if($category->photos->count() > 0)
                    <div class="pt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($category->photos as $photo)
                        
                            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition">
                                <div class="relative">
                                    <img src="{{ $photo->image ? asset('storage/'.$photo->image) : 'https://via.placeholder.com/400x300' }}" 
                                         alt="{{ $photo->name ?? 'Untitled' }}"
                                         class="h-40 w-full object-cover">
                                
                                </div>
                                <div class="p-4">
                                    <div class="text-sm font-semibold text-gray-800 mb-1">
                                        {{ $photo->name ?? 'Untitled' }}
                                    </div>

                                    <div class="flex justify-between items-center mb-3">
                                    {{-- Views & Likes --}}
                                    <div class="text-xs text-gray-600 flex items-center gap-4">
                                        <span class="inline-flex items-center gap-1 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                                    <path d="M21 12q-3.6 6-9 6t-9-6q3.6-6 9-6t9 6" />
                                                </g>
                                            </svg>
                                            <span class="font-medium">{{ $photo->view_count ?? 0 }}</span>
                                        </span>

                                        <a href="{{ route('admin.comments.likes-stats') }}" class="inline-flex items-center gap-1 text-red-600 hover:opacity-90 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                            <span class="font-medium">{{ $photo->likes()->count() }}</span>
                                        </a>

                                        <a href="{{ route('admin.comments.facility-photos', ['category_id' => $category->id, 'photo_id' => $photo->id]) }}" class="inline-flex items-center gap-1 text-blue-600 hover:opacity-90 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M7 10h10M7 14h7" />
                                                <path d="M5 5h14a2 2 0 0 1 2 2v9l-3-2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" fill-opacity=".15" />
                                            </svg>
                                            <span class="font-medium">{{ $photo->comments()->count() }}</span>
                                        </a>
                                    </div>

                                    {{-- Edit & Delete --}}
                                    <div class="flex items-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.facilities.categories.photos.edit', [$category->id, $photo->id]) }}"
                                        class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 21h18" />
                                                <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                                <path d="M14 6l4 4" />
                                                <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.facilities.categories.photos.destroy', [$category->id, $photo->id]) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this photo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-500 hover:text-red-600 flex items-center justify-center">
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
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 mt-2">No photos in this category.</p>
                @endif
            </div>
        @endforeach
    </section>
</div>
@endsection
