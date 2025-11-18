@extends('layouts.manage')

@section('title', 'School Profile')

@section('content')
<div class="min-h-screen bg-white text-gray-800 px-6 lg:px-10 py-10">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] bg-clip-text text-transparent">
            School Profile Overview
        </h1>
        <p class="text-gray-500 mt-2">Discover and manage the legacy of Hogwarts School of Witchcraft and Wizardry</p>
    </div>

    {{-- Hero Image --}}
    @if($profile->hero_image)
        <div class="mb-12">
            <img src="{{ asset('storage/' . $profile->hero_image) }}" 
                 alt="Hero Image" 
                 class="w-full h-72 object-cover rounded-2xl shadow-md"
                 onerror="this.style.display='none'">
        </div>
    @endif

    {{-- School Logo, Name & Edit Button --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
        <div class="flex items-center mb-4 md:mb-0">
            @if($profile->logo)
                <img src="{{ asset('storage/' . $profile->logo) }}" 
                     alt="School Logo" class="h-20 w-20 object-contain mr-4 rounded-xl shadow"
                     onerror="this.style.display='none'">
            @endif
            <h1 class="text-3xl font-bold text-gray-900">{{ $profile->title ?? '-' }}</h1>
        </div>

        {{-- Manage Button --}}
        <a href="{{ route('admin.school-profile.edit') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#425d9e] via-[#3c5e5e] to-[#b03535] text-white rounded-xl font-semibold shadow-md hover:shadow-lg hover:scale-[1.02] transition-all">
            <i class="fas fa-edit"></i> Manage Profile
        </a>
    </div>

    {{-- Founding Info --}}
    <section class="bg-gray-50 rounded-2xl shadow p-8 mb-10">
        

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-white rounded-2xl shadow p-6">
            <div>
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
                Founding Information
            </h2>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase">Founded Year</p>
                <p class="text-lg text-gray-800">{{ $profile->founded_year ?? '-' }}</p>
            </div>
            </div>
            
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase">Founding History</p>
                <p class="text-lg text-gray-800">{{ $profile->history ?? '-' }}</p>
            </div>

            
            
        </div>

        {{-- Founders Grid --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Founders of Hogwarts</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($profile->founders as $founder)
                    <div class="bg-gray-50 rounded-xl shadow-sm p-5 flex flex-col items-center text-center hover:shadow-md transition">
                        <img src="{{ asset('storage/' . $founder->photo) }}" 
                             alt="{{ $founder->name }}" 
                             class="h-24 w-24 rounded-full object-cover mb-3 border-2 border-gray-200">
                        <h4 class="text-lg font-bold text-gray-800">{{ $founder->name }}</h4>
                        <p class="text-sm text-gray-500 mb-2">Born {{ $founder->birth_year }}</p>
                        <p class="text-gray-600 text-sm">{{ $founder->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- About + Vision & Mission --}}
    <section class="bg-gray-50 rounded-2xl shadow p-8 mb-10">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
            About, Vision & Mission
        </h2>

        <p class="text-gray-700 whitespace-pre-line mb-6">{{ $profile->about ?? '-' }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Vision</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $profile->vision ?? '-' }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Mission</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $profile->mission ?? '-' }}</p>
            </div>
        </div>
    </section>

    {{-- Contact + Social Media + Location --}}
    <section>
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#425d9e] via-[#3c5e5e] to-[#b03535] rounded-full"></span>
            Contact Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Contact & Address --}}
            <div class="bg-gray-50 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact & Address</h3>
                <p class="text-sm font-medium text-gray-500">Address</p>
                <p class="text-gray-700 mb-3">{{ $profile->address ?? '-' }}</p>

                <p class="text-sm font-medium text-gray-500">Phone</p>
                <p class="text-gray-700 mb-3">{{ $profile->phone ?? '-' }}</p>

                <p class="text-sm font-medium text-gray-500">Email</p>
                <p class="text-gray-700">{{ $profile->email ?? '-' }}</p>
            </div>

            {{-- Social Media --}}
            <div class="bg-gray-50 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Social Media</h3>
                <ul class="space-y-2 text-sm">
                    @if($profile->facebook_url)
                        <li><i class="fab fa-facebook text-blue-600 mr-1"></i> 
                            <a href="{{ $profile->facebook_url }}" target="_blank" class="hover:underline">{{ $profile->facebook_url }}</a>
                        </li>
                    @endif
                    @if($profile->instagram_url)
                        <li><i class="fab fa-instagram text-pink-600 mr-1"></i> 
                            <a href="{{ $profile->instagram_url }}" target="_blank" class="hover:underline">{{ $profile->instagram_url }}</a>
                        </li>
                    @endif
                    @if($profile->youtube_url)
                        <li><i class="fab fa-youtube text-red-600 mr-1"></i> 
                            <a href="{{ $profile->youtube_url }}" target="_blank" class="hover:underline">{{ $profile->youtube_url }}</a>
                        </li>
                    @endif
                    @if($profile->twitter_url)
                        <li><i class="fab fa-twitter text-sky-600 mr-1"></i> 
                            <a href="{{ $profile->twitter_url }}" target="_blank" class="hover:underline">{{ $profile->twitter_url }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Location --}}
            <div class="bg-gray-50 rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Location</h3>
                @if($profile->map_embed)
                    <div class="aspect-w-4 aspect-h-9 rounded-xl overflow-hidden">
                        {!! $profile->map_embed !!}
                    </div>
                @else
                    <p class="text-gray-600">No map available</p>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Cek apakah baru saja sukses update
    if (sessionStorage.getItem('updateSuccess')) {
        Swal.fire({
            title: 'Success!',
            text: 'School profile updated successfully!',
            icon: 'success',
            confirmButtonColor: '#3b82f6',
            timer: 2000,
            showConfirmButton: false,
        });
        // Hapus flag supaya nggak muncul lagi kalau reload manual
        sessionStorage.removeItem('updateSuccess');
    }
});
</script>
@endsection