@extends('layouts.manage')

@section('title', 'Edit School Profile')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'School Profile', 'route' => route('admin.school-profile.index')],
        ['label' => 'Edit School Profile', 'route' => null],
    ];
@endphp

@section('content')
    <div class="mb-4 flex items-center">
        <a href="{{ route('admin.school-profile.index') }}" class="flex items-center gap-2 px-3 py-1.5 bg-gray-200 hover:bg-gray-300 
            text-gray-700 rounded-md font-semibold transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

@if (session('error'))
<div id="updateErrorBanner" 
     class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl flex items-center justify-between shadow-md transition-all duration-300">
    <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M12 5a7 7 0 00-7 7v5h14v-5a7 7 0 00-7-7z" />
        </svg>
        <span class="font-semibold text-sm md:text-base">
            {{ session('error') ?? 'Failed to update changes. Please try again.' }}
        </span>
    </div>
    <button onclick="document.getElementById('updateErrorBanner').classList.add('hidden')" 
            class="text-red-500 hover:text-red-700 font-bold text-lg">
        ×
    </button>
</div>
@endif

{{-- Tempat muncul alert dari AJAX --}}
<div id="ajaxErrorBanner" 
     class="hidden mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl flex items-center justify-between shadow-md">
    <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M12 5a7 7 0 00-7 7v5h14v-5a7 7 0 00-7-7z" />
        </svg>
        <span id="ajaxErrorMessage" class="font-semibold text-sm md:text-base">Failed to update changes.</span>
    </div>
    <button onclick="document.getElementById('ajaxErrorBanner').classList.add('hidden')" 
            class="text-red-500 hover:text-red-700 font-bold text-lg">
        ×
    </button>
</div>


<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.school-profile.update', $profile->id) }}" 
        method="POST" enctype="multipart/form-data" 
        class="space-y-6">
        @csrf
        @method('PUT')
        {{-- ABOUT SCHOOL --}}
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                About School
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">School Name <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $profile->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500" required>
                </div>

                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">School Logo</label>
                    <div class="flex items-start space-x-4">
                        @if($profile->logo)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $profile->logo) }}" alt="School Logo" class="h-16 w-auto rounded-md shadow" onerror="this.style.display='none'">
                            </div>
                        @endif
                        <div class="flex-grow">
                            <input type="file" name="logo" id="logo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG — Max 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label for="about" class="block text-sm font-medium text-gray-700 mb-1">About School</label>
                <textarea name="about" id="about" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">{{ old('about', $profile->about) }}</textarea>
            </div>
        </div>

        {{-- CONTACT & ADDRESS --}}
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                Contact & Address
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">{{ old('address', $profile->address) }}</textarea>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $profile->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label for="map_embed" class="block text-sm font-medium text-gray-700 mb-1">Google Maps Embed</label>
                <textarea name="map_embed" id="map_embed" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">{{ old('map_embed', $profile->map_embed) }}</textarea>
            </div>
        </div>

        {{-- VISION & MISSION --}}
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                Vision & Mission
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vision" class="block text-sm font-medium text-gray-700 mb-1">Vision</label>
                    <textarea name="vision" id="vision" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">{{ old('vision', $profile->vision) }}</textarea>
                </div>

                <div>
                    <label for="mission" class="block text-sm font-medium text-gray-700 mb-1">Mission</label>
                    <textarea name="mission" id="mission" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-amber-500">{{ old('mission', $profile->mission) }}</textarea>
                </div>
            </div>
        </div>

        {{-- SOCIAL MEDIA --}}
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                Social Media
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                    <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $profile->facebook_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $profile->instagram_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500">
                </div>

                <div>
                    <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-1">YouTube</label>
                    <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $profile->youtube_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500">
                </div>

                <div>
                    <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                    <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $profile->twitter_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
        </div>

        {{-- HERO & FOUNDING INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                    Hero Section
                </h2>

                <div>
                    <label for="hero_image" class="block text-sm font-medium text-gray-700 mb-1">Hero Image</label>

                    {{-- Existing Hero Preview --}}
                    @if($profile->hero_image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $profile->hero_image) }}" 
                                alt="Current Hero Image"
                                onerror="this.style.display='none'" 
                                class="h-32 w-auto rounded-lg shadow">
                        </div>
                    @endif

                    {{-- Drop Zone Upload --}}
                    <div id="dropArea" 
                        class="w-full h-60 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center relative border-2 border-dashed border-gray-300 cursor-pointer transition hover:border-indigo-400">
                        <img id="photoPreview" src="#" class="object-cover w-full h-full hidden">
                        <span id="photoPlaceholder" class="text-gray-400 text-center px-2">Drop or paste hero image</span>
                        <input type="file" name="hero_image" id="hero_image" accept="image/*" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    <small class="text-gray-500 text-center block mt-2">
                        Drag & drop image, paste (Ctrl/Cmd + V), or click to select
                    </small>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg mb-8">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                    Founding Information
                </h2>

                <label for="founded_year" class="block text-sm font-medium text-gray-700 mb-1">Founded Year</label>
                <input type="number" name="founded_year" id="founded_year" value="{{ old('founded_year', $profile->founded_year) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500">

                <div class="mt-6">
                <label for="history" class="block text-sm font-medium text-gray-700 mb-1">
                    Founding History
                </label>
                <textarea 
                    id="history" 
                    name="history" 
                    rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500"
                    placeholder="Tell the story of how the school was founded..."
                >{{ old('history', $profile->history) }}</textarea>
            </div>
            </div>
        </div>

        {{-- FOUNDERS --}}
        <div class="bg-gray-50 p-6 rounded-lg mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                    Founders
                </h2>
                <a href="{{ route('admin.school-profile.founders.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] text-white rounded-lg font-semibold shadow-md hover:shadow-lg hover:scale-[1.02] transition-all">
                    <i class="fas fa-plus"></i>
                    Add Founder
                </a>
            </div>

            @if($profile->founders->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($profile->founders as $founder)
                        <a href="{{ route('admin.school-profile.founders.edit', $founder->id) }}" class="relative block bg-white rounded-lg shadow-sm p-4 text-center hover:shadow-md transition overflow-hidden">
                            <div class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="w-5 h-5">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                    </g>
                                </svg>
                            </div>

                            <img src="{{ $founder->photo ? asset('storage/' . $founder->photo) : asset('images/default-avatar.png') }}" alt="{{ $founder->name }}" class="h-20 w-20 rounded-full object-cover mx-auto mb-3 border-2 border-gray-300" onerror="this.src='{{ asset('images/hogwarts.jpg') }}'">
                            <h4 class="text-md font-bold text-gray-800">{{ $founder->name }}</h4>
                            <p class="text-sm text-gray-500">Born {{ $founder->birth_year }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($founder->description, 60) }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-users text-5xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No founders added yet.</p>
                    <a href="{{ route('admin.school-profile.founders.create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-plus"></i>
                        Add First Founder
                    </a>
                </div>
            @endif
        </div>

        {{-- SAVE BUTTON --}}
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] text-white rounded-xl font-semibold shadow-md hover:shadow-lg hover:scale-[1.02] transition-all" id="submitBtn">
                <i class="fas fa-save mr-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const dropArea = document.getElementById('dropArea');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const inputFile = document.getElementById('hero_image');

    // 🖼️ Preview upload
    inputFile.addEventListener('change', handleFile);
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('border-indigo-400');
    });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('border-indigo-400'));
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('border-indigo-400');
        if (e.dataTransfer.files.length > 0) {
            inputFile.files = e.dataTransfer.files;
            handleFile();
        }
    });

    // 🖼️ Paste handler (Ctrl+V)
    document.addEventListener('paste', (e) => {
        const items = e.clipboardData?.items;
        if (!items) return;

        for (const item of items) {
            if (item.type.indexOf('image') !== -1) {
                const file = item.getAsFile();
                const dt = new DataTransfer();
                dt.items.add(file);
                inputFile.files = dt.files;
                handleFile();
            }
        }
    });

    function handleFile() {
        const file = inputFile.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.src = e.target.result;
            photoPreview.classList.remove('hidden');
            photoPlaceholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    // ✅ SweetAlert success (sessionStorage)
    if (sessionStorage.getItem('updateSuccess')) {
        Swal.fire({
            title: 'Success!',
            text: 'School profile updated successfully!',
            icon: 'success',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        sessionStorage.removeItem('updateSuccess');
    }

    // 🚀 Async submit with fetch
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const originalButtonText = submitBtn.innerHTML;

        // Ubah tombol jadi loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="inline-block animate-spin mr-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </span> Saving...
        `;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT',
                },
            });

            const text = await response.text();
            let result;
            try {
                result = JSON.parse(text);
            } catch {
                throw new Error('Invalid JSON response from server');
            }

            if (result.success) {
                await Swal.fire({
                    title: 'Success!',
                    text: result.message || 'School profile successfully updated!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                });

                sessionStorage.setItem('updateSuccess', '1');
                window.location.href = result.redirect || "{{ route('admin.school-profile.index') }}";
            } else {
                throw new Error(result.message || 'Failed to update profile');
            }
        }catch (error) {
    // 🔹 Munculkan banner error
    const banner = document.getElementById('ajaxErrorBanner');
    const msg = document.getElementById('ajaxErrorMessage');
    msg.textContent = error.message || 'Failed to update changes.';
    banner.classList.remove('hidden');
    banner.classList.add('flex');

    Swal.fire({
        title: 'Error!',
        text: error.message,
        icon: 'error',
        confirmButtonColor: '#ef4444',
    });

        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalButtonText;
        }
    });
});
</script>



@endpush

@endsection
