{{-- resources/views/admin/achievements/create.blade.php --}}
@extends('layouts.manage')

@section('title', 'Add Achievement')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Achievements', 'route' => route('admin.achievements.index')],
        ['label' => 'Add New', 'route' => null],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6">

    {{-- Header --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold bg-clip-text text-transparent 
                   bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] drop-shadow-sm">
            Add New Achievement
        </h1>
        <p class="text-gray-600 mt-2 text-sm">Celebrate Hogwarts students’ and houses’ accomplishments.</p>
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
    <div class="min-h-screen bg-white text-gray-800 px-6 lg:px-10 py-10">
        <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] focus:border-transparent transition">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] focus:border-transparent transition">{{ old('description') }}</textarea>
            </div>

            {{-- House --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">House</label>
                <select name="house_id"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] focus:border-transparent transition">
                    <option value="">-- No House --</option>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}" 
                            {{ (string)old('house_id', $house_id ?? '') === (string)$house->id ? 'selected' : '' }}>
                            {{ $house->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                <input type="date" name="date" value="{{ old('date') }}"
                    class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                           focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] focus:border-transparent transition">
            </div>

            {{-- Image --}}
            <div>
                <div id="dropArea" class="w-48 h-60 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center relative border-2 border-dashed border-gray-300 cursor-pointer">
                    <img id="imagePreview" src="#" class="object-cover w-full h-full hidden">
                    <span id="imagePlaceholder" class="text-gray-400 text-center px-2">Image</span>
                    <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                <small class="text-gray-500 text-center block mt-2">Drag & drop image, paste (Ctrl/Cmd+V), or click to select</small>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('admin.achievements.index') }}"
                   class="px-6 py-2.5 rounded-xl border border-gray-300 bg-gray-100 
                          text-gray-700 font-medium hover:bg-gray-200 transition shadow-sm">
                    Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white 
                           bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e]
                           hover:opacity-90 shadow-md transition">
                    Save Achievement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const dropArea = document.getElementById('dropArea');
    const photoInput = dropArea.querySelector('input[type="file"]');
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');

    dropArea.addEventListener('click', () => photoInput.click());

    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('border-blue-500');
    });
    dropArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropArea.classList.remove('border-blue-500');
    });
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('border-blue-500');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    dropArea.addEventListener('paste', (e) => {
        const items = e.clipboardData.items;
        for (let item of items) {
            if (item.type.indexOf('image') !== -1) {
                const file = item.getAsFile();
                handleFile(file);
            }
        }
    });

    photoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) handleFile(file);
    });

    function handleFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);

        // Set file ke input untuk submit
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        photoInput.files = dataTransfer.files;
    }

</script>
@endsection
