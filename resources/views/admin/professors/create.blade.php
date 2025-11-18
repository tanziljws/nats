{{-- resources/views/admin/professors/create.blade.php --}}
@extends('layouts.manage')

@section('title', 'Add Professor')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Professors', 'route' => route('admin.professors.index')],
        ['label' => 'Add New', 'route' => null],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6">

    {{-- Header --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold bg-clip-text text-transparent 
                   bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] drop-shadow-sm">
            Add New Professor
        </h1>
        <p class="text-gray-600 mt-2 text-sm">Register Hogwarts professors into the system.</p>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-5 py-4 rounded-xl shadow-sm">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white text-gray-800 px-6 lg:px-10 py-10 rounded-2xl shadow-sm">
        <form action="{{ route('admin.professors.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
            </div>

            {{-- Position --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Position</label>
                <input type="text" name="position" value="{{ old('position') }}" required
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
            </div>

            {{-- Subject --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
            </div>

            {{-- House --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">House</label>
                <select name="house_id"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
                    <option value="">Select House</option>
                    @foreach($houses as $house)
                        <option value="{{ $house->id }}" {{ old('house_id') == $house->id ? 'selected' : '' }}>
                            {{ $house->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('admin.professors.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-gray-300 bg-gray-100 
                          text-gray-700 font-medium hover:bg-gray-200 transition shadow-sm">
                    Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white 
                           bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e]
                           hover:opacity-90 shadow-md transition">
                    Save Professor
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
