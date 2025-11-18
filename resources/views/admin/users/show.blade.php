@extends('layouts.manage')

@section('title', 'User Details')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Users', 'route' => route('admin.users.index')],
        ['label' => $user->name, 'route' => null],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold bg-clip-text text-transparent 
                   bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] drop-shadow-sm">
            User Details
        </h1>
        <p class="text-gray-600 mt-2 text-sm">View and manage user information and activity</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2 max-w-5xl mx-auto">
            <i class="fas fa-check-circle text-green-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <!-- User Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                
                {{-- Header --}}
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-1.5 h-5 bg-[#3c5e5e] rounded"></span>
                    <span class="text-xs font-semibold tracking-wide uppercase text-[#3c5e5e]">
                        User Profile
                    </span>
                </div>

                {{-- Avatar --}}
                <div class="text-center mb-6">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                            class="w-28 h-28 rounded-full object-cover mx-auto mb-4 shadow-sm border border-gray-200">
                    @else
                        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 
                                    flex items-center justify-center text-white font-bold text-4xl mx-auto mb-4 shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 text-sm">{{ $user->email }}</p>

                    {{-- Status --}}
                    <div class="mt-3">
                        @if($user->status == 'active')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                Active
                            </span>
                        @elseif($user->status == 'banned')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                Banned
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="space-y-3 border-t pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">User ID:</span>
                        <span class="font-semibold text-gray-900">{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Registered:</span>
                        <span class="font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Last Login:</span>
                        <span class="font-semibold text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-center gap-5">
                    {{-- Edit --}}
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18" /><path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                        </svg>
                    </a>

                    {{-- Ban / Activate --}}
                    @if($user->status == 'active')
                        <form action="{{ route('admin.users.ban', $user->id) }}" method="POST"
                            onsubmit="return confirm('Ban this user?')">
                            @csrf
                            <button class="text-orange-500 hover:text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="5" y1="5" x2="19" y2="19"></line>
                                </svg>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST"
                            onsubmit="return confirm('Activate this user?')">
                            @csrf
                            <button class="text-green-600 hover:text-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="9 12 12 15 16 10"></polyline>
                                </svg>
                            </button>
                        </form>
                    @endif

                    {{-- Delete --}}
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                        onsubmit="return confirm('Delete this user permanently?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1V6"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Activity Stats -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 mb-6 hover:shadow-lg transition">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-8 bg-gradient-to-b from-[#b03535] via-[#3c5e5e] to-[#425d9e] rounded-full"></span>
                    <h3 class="text-xl font-bold text-gray-800">Activity Statistics</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    {{-- Facility Likes --}}
                    <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-[#425d9e] uppercase mb-1">Facility Photos</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $user->facilityPhotoLikes->count() }}</h3>
                                <p class="text-sm text-gray-500">Total Likes</p>
                            </div>
                            <i class="fas fa-thumbs-up text-4xl text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Facility Comments --}}
                    <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-[#3c5e5e] uppercase mb-1">Facility Photos</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $user->facilityPhotoComments->count() }}</h3>
                                <p class="text-sm text-gray-500">Total Comments</p>
                            </div>
                            <i class="fas fa-comment text-4xl text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Prophet Likes --}}
                    <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-[#3c5e5e] uppercase mb-1">Hogwarts Prophet</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $user->hogwartsProphetLikes->count() }}</h3>
                                <p class="text-sm text-gray-500">Total Likes</p>
                            </div>
                            <i class="fas fa-newspaper text-4xl text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Prophet Comments --}}
                    <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-[#425d9e] uppercase mb-1">Hogwarts Prophet</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $user->hogwartsProphetComments->count() }}</h3>
                                <p class="text-sm text-gray-500">Total Comments</p>
                            </div>
                            <i class="fas fa-comment text-4xl text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Achievement Likes --}}
                    <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-[#b03535] uppercase mb-1">Achievements</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $user->achievementLikes->count() }}</h3>
                                <p class="text-sm text-gray-500">Total Likes</p>
                            </div>
                            <i class="fas fa-trophy text-4xl text-gray-300"></i>
                        </div>
                    </div>

                    {{-- Achievement Comments --}}
                    <div class="rounded-2xl bg-white shadow-md border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-[#b03535] uppercase mb-1">Achievements</p>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $user->achievementComments->count() }}</h3>
                                <p class="text-sm text-gray-500">Total Comments</p>
                            </div>
                            <i class="fas fa-comment text-4xl text-gray-300"></i>
                        </div>
                    </div>

                </div>
            </div>

    </div>
</div>
@endsection
