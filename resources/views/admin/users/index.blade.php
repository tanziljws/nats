{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.manage')

@section('title', 'User Management')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Users', 'route' => null],
    ];
@endphp

@section('content')
<div class="text-center mb-12">
    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
        User Management
    </h1>
    <p class="text-gray-500 mt-2">Manage users, ban or delete accounts in the Hogwarts network</p> 
</div>

<div class="min-h-screen bg-white text-gray-800 px-6 py-10">

    {{-- ========== FILTER SECTION ========== --}}
    <div class="mb-6 bg-white rounded-xl shadow-md p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4 items-center">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search by name or email..." 
                   class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">

            <select name="status" class="min-w-[150px] px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                <option value="banned" {{ request('status')=='banned' ? 'selected' : '' }}>Banned</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
            </select>

            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#3c5e5e] to-[#425d9e] text-white rounded-xl hover:opacity-90 transition">
                <i class="fas fa-search mr-2"></i> Search
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition">
                <i class="fas fa-redo mr-2"></i> Reset
            </a>
        </form>
    </div>

    {{-- ========== USERS TABLE ========== --}}
    <div class="overflow-x-auto rounded-2xl bg-white shadow-md border border-gray-200">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="py-3 px-4">User</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Registered</th>
                    <th class="py-3 px-4">Last Login</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        {{-- User Info --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="text-gray-900 font-medium">{{ $user->name }}</div>
                                    <div class="text-gray-500 text-xs">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="py-3 px-4 text-gray-900">{{ $user->email }}</td>

                        {{-- Status --}}
                        <td class="py-3 px-4">
                            @if($user->status == 'active')
                                <span class="px-2 py-1 text-xs rounded-xl bg-green-100 text-green-800">Active</span>
                            @elseif($user->status == 'banned')
                                <span class="px-2 py-1 text-xs rounded-xl bg-red-100 text-red-800">Banned</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-xl bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>

                        {{-- Registered --}}
                        <td class="py-3 px-4 text-gray-500 text-sm">{{ $user->formatted_created_at }}</td>

                        {{-- Last Login --}}
                        <td class="py-3 px-4 text-gray-500 text-sm">{{ $user->last_login_diff }}</td>

                        {{-- Actions --}}
                        <td class="py-3 px-4 text-right flex justify-end gap-3">
                            {{-- View --}}
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                               class="text-blue-600 hover:text-blue-700 flex items-center justify-center" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                    <path d="M21 12q-3.6 6-9 6t-9-6q3.6-6 9-6t9 6" />
                                </svg>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18" />
                                    <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                    <path d="M14 6l4 4" />
                                    <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                                </svg>
                            </a>

                            {{-- Ban / Activate --}}
                            @if($user->status == 'active')
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" onsubmit="return confirm('Ban this user?')">
                                    @csrf
                                    <button type="submit" class="text-orange-500 hover:text-orange-600 flex items-center justify-center" title="Ban">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22a10 10 0 1 0 0 -20a10 10 0 0 0 0 20Z" />
                                            <path d="M5.7 5.7l12.6 12.6" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" onsubmit="return confirm('Activate this user?')">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-700 flex items-center justify-center" title="Activate">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22a10 10 0 1 0 0 -20a10 10 0 0 0 0 20Z" />
                                            <path d="M9 12l2 2l4 -4" />
                                        </svg>
                                    </button>
                                </form>
                            @endif

                            {{-- Delete --}}
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 flex items-center justify-center" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h5c0.5 0 1 -0.5 1 -1v-14M12 20h-5c-0.5 0 -1 -0.5 -1 -1v-14" />
                                        <path d="M4 5h16" />
                                        <path d="M10 4h4M10 9v7M14 9v7" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $users->onEachSide(1)->links('vendor.pagination.clean') }}
    </div>
</div>
@endsection
