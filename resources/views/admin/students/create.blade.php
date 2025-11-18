@extends('layouts.manage')

@section('title', 'Add Student')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Students', 'route' => route('admin.students.index')],
        ['label' => 'Add New', 'route' => null],
    ];
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6">

    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold bg-clip-text text-transparent 
                   bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] drop-shadow-sm">
            Add New Student
        </h1>
        <p class="text-gray-600 mt-2 text-sm">Register a new student.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-5 py-4 rounded-xl shadow-sm max-w-3xl mx-auto">
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white p-8 rounded-3xl shadow-lg max-w-3xl mx-auto flex flex-col gap-6 items-center">
            {{-- Photo --}}
            <div id="dropArea" class="w-48 h-60 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center relative border-2 border-dashed border-gray-300 cursor-pointer">
                <img id="photoPreview" src="#" class="object-cover w-full h-full hidden">
                <span id="photoPlaceholder" class="text-gray-400 text-center px-2">Photo</span>
                <input type="file" name="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
            <small class="text-gray-500 text-center block mt-2">Click to select or drop an image</small>

            {{-- Input fields --}}
            <div class="w-full flex flex-col gap-4 mt-4">
                <div>
                    <label class="block font-medium mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                                  focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
                </div>

                <div>
                    <label class="block font-medium mb-1">Birth Date</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                           class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                                  focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
                </div>

                <div>
                    <label class="block font-medium mb-1">Year</label>
                    <input type="text" name="year" value="{{ old('year') }}" required
                           class="w-full border border-gray-300 px-4 py-3 rounded-xl 
                                  focus:outline-none focus:ring-2 focus:ring-[#3c5e5e] transition">
                </div>

                <div>
                    <label class="block font-medium mb-1">House</label>
                    <select name="house_id" required
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

                {{-- Actions --}}
                <div class="flex justify-end gap-4 pt-6">
                    <a href="{{ route('admin.students.index') }}"
                       class="px-6 py-2.5 rounded-xl border border-gray-300 bg-gray-100 
                              text-gray-700 font-medium hover:bg-gray-200 transition shadow-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl font-semibold text-white 
                                   bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e]
                                   hover:opacity-90 shadow-md transition">
                        Save Student
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const dropArea = document.getElementById('dropArea');
const photoInput = dropArea.querySelector('input[type="file"]');
const preview = document.getElementById('photoPreview');
const placeholder = document.getElementById('photoPlaceholder');

// Klik untuk pilih file
dropArea.addEventListener('click', () => photoInput.click());

// Drag & Drop
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

// Paste dari clipboard
dropArea.addEventListener('paste', (e) => {
    const items = e.clipboardData.items;
    for (let item of items) {
        if (item.type.indexOf('image') !== -1) {
            const file = item.getAsFile();
            handleFile(file);
        }
    }
});

// Input file biasa
photoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) handleFile(file);
});

// Fungsi preview + set file ke input
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
