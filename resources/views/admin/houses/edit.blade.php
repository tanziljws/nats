@extends('layouts.manage')

@section('title', 'Edit ' . $house->name)

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Houses', 'route' => route('admin.houses.index')],
        ['label' => 'Edit '.$house->name , 'route' => null]
    ];

    // warna badge per house
    $houseColors = [
        1 => '#b03535', // Gryffindor
        2 => '#2a623d', // Slytherin
        3 => '#ecb939', // Hufflepuff
        4 => '#425d9e', // Ravenclaw
    ];
@endphp

@section('content')

    <div class="max-w-6xl mx-auto px-6 py-10 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Edit House Form (1/3) --}}
                <div class="md:col-span-1 bg-white rounded-2xl shadow-sm p-6">
                    <form action="{{ route('admin.houses.update', $house->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Legacy / Description</label>
                            <textarea name="description" rows="4"
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">{{ $house->description }}</textarea>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Characteristics (comma separated)</label>
                            <input type="text" name="characteristics"
                                value="{{ is_array($house->characteristics) ? implode(',', $house->characteristics) : $house->characteristics }}"
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Logo</label>
                            @if($house->logo)
                                <img src="{{ asset('storage/' . $house->logo) }}" alt="Logo"
                                    class="w-20 h-20 mb-3 rounded-lg object-cover border border-gray-200">
                            @endif
                            <input type="file" name="logo"
                                class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-5 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                                Update House
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Achievements (2/3) --}}
                <div class="md:col-span-2 bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Achievements in {{ $house->name }}</h2>
                        <div id="achievementAlertContainer"></div>
                        
                        <div class="flex items-center gap-2">
                            {{-- Button Add Achievement --}}
                            <button id="openAchievementModal"
                                class="px-5 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                                Add New Achievement
                            </button>

                            {{-- View All Button --}}
                            <a href="{{ route('admin.achievements.index') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-800 rounded-xl shadow hover:bg-gray-200 transition text-sm font-medium">
                                View All ->
                            </a>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        @forelse ($achievements as $item)
                            <div class="flex flex-col justify-between flex-grow">
                                <div class="flex items-center gap-4 bg-gray-50 rounded-xl shadow-sm p-4 hover:shadow-md transition cursor-pointer">
                                    <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                        @if ($item->image && file_exists(public_path('storage/' . $item->image)))
                                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                <i class="fas fa-trophy text-xl opacity-40"></i>
                                            </div>
                                        @endif
                                    </div>
                                
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-gray-800">{{ $item->title }}</h3>
                                        
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($item->description, 100) }}</p>

                                        <div>
                                            <div class="mt-2 flex flex-col md:flex-row justify-between items-start md:items-center">
                                                <div class="flex items-center gap-3">
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

                                                    <span class="text-xs text-gray-400">
                                                        {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '-' }}
                                                    </span>
                                                </div>

                                                <div class="flex gap-3 mt-2 md:mt-0">
                                                    {{-- Edit --}}
                                                    <button type="button"
                                                        onclick='openEditAchievementModal(@json($item))'
                                                        class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 21h18" />
                                                            <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                                            <path d="M14 6l4 4" />
                                                            <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                                                        </svg>
                                                    </button>


                                                    {{-- Delete --}}
                                                    <form action="{{ route('admin.achievements.destroy', $item->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this achievement?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-500 hover:text-red-600 flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block"
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
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500">No achievements found.</div>
                        @endforelse
                    </div>
                

                    {{-- Modal --}}
                    <div id="achievementModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
                            <button onclick="closeAchievementModal()"
                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                            
                            <h3 class="text-lg font-semibold mb-4 text-gray-800">Add New Achievement</h3>

                            <form action="{{ route('admin.houses.storeAchievement', $house->id) }}" method="POST" enctype="multipart/form-data"  class="space-y-4">
                                @csrf

                                {{-- Title --}}
                                <div>
                                    <label class="block font-semibold mb-1">Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}" required
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                                </div>

                                {{-- Description --}}
                                <div>
                                    <label class="block font-semibold mb-1">Description</label>
                                    <textarea name="description" rows="4"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">{{ old('description') }}</textarea>
                                </div>

                                <input type="hidden" name="house_id" value="{{ $house->id }}">

                                {{-- Date --}}
                                <div>
                                    <label class="block font-semibold mb-1">Date</label>
                                    <input type="date" name="date" value="{{ old('date') }}"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Image</label>
                                    <input id="createImage" type="file" name="image" accept="image/*"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                                    <div id="createPreview" class="w-full h-48 bg-gray-100 rounded-xl mt-3 flex items-center justify-center overflow-hidden">
                                        <i class="fas fa-trophy text-4xl opacity-40"></i>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex justify-end gap-3 pt-3">
                                    <button type="button" onclick="closeAchievementModal()"
                                        class="px-4 py-2 bg-gray-300 rounded-xl hover:bg-gray-400 transition">Cancel</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                                        Save Achievement
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        const achievementModal = document.getElementById('achievementModal');
                        const openModalBtn = document.getElementById('openAchievementModal');
                        openModalBtn.addEventListener('click', () => {
                            achievementModal.classList.remove('hidden');
                            achievementModal.classList.add('flex');
                        });
                        function closeAchievementModal() {
                            achievementModal.classList.add('hidden');
                            achievementModal.classList.remove('flex');
                        }

                        (function(){
                            const input = document.getElementById('createImage');
                            const preview = document.getElementById('createPreview');
                            if(input && preview){
                                input.addEventListener('change', (e)=>{
                                    const file = e.target.files && e.target.files[0];
                                    if(!file){
                                        preview.innerHTML = '<i class="fas fa-trophy text-4xl opacity-40"></i>';
                                        return;
                                    }
                                    const reader = new FileReader();
                                    reader.onload = ev => {
                                        preview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
                                    };
                                    reader.readAsDataURL(file);
                                });
                            }
                        })();
                    </script>

                    {{-- Modal Edit --}}
                    <div id="editAchievementModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl p-8 relative flex flex-col md:flex-row gap-6">
                            <button onclick="closeEditAchievementModal()"
                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>

                            <div class="flex-1">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800">Edit Achievement</h3>
                                <form id="editAchievementForm" method="POST" enctype="multipart/form-data" onsubmit="submitEditAchievement(event)" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label class="block font-semibold mb-1">Title</label>
                                        <input type="text" name="title" id="editTitle" required
                                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                                    </div>

                                    <div>
                                        <label class="block font-semibold mb-1">Description</label>
                                        <textarea name="description" id="editDescription" rows="4"
                                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none"></textarea>
                                    </div>

                                    <div>
                                        <label class="block font-semibold mb-1">House</label>
                                        <select name="house_id" id="editHouse"
                                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                                            <option value="">-- No House --</option>
                                            @foreach ($houses as $h)
                                                <option value="{{ $h->id }}">{{ $h->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block font-semibold mb-1">Date</label>
                                        <input type="date" name="date" id="editDate"
                                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#3c5e5e] focus:outline-none">
                                    </div>
                                    </div>

                                    <div class="w-full md:w-1/3 flex flex-col justify-center">
                                        <label class="block font-semibold mb-2">Image</label>
                                        <div id="editPreview" class="w-full h-60 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden">
                                            <i class="fas fa-trophy text-5xl opacity-50"></i>
                                        </div>
                                        <input type="file" name="image" id="editImageModal" class="mt-3 border border-gray-300 rounded-xl px-3 py-2">
                                    

                                    <div class="flex justify-end gap-3 pt-3">
                                        <button type="button" onclick="closeEditAchievementModal()"
                                            class="px-4 py-2 bg-gray-300 rounded-xl hover:bg-gray-400 transition">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openEditAchievementModal(achievement) {
                            const modal = document.getElementById('editAchievementModal');
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');

                            document.getElementById('editTitle').value = achievement.title || '';
                            document.getElementById('editDescription').value = achievement.description || '';
                            if (achievement.date) {
                                const formattedDate = achievement.date.split('T')[0] || achievement.date.split(' ')[0];
                                document.getElementById('editDate').value = formattedDate;
                            } else {
                                document.getElementById('editDate').value = '';
                            }
                            document.getElementById('editHouse').value = achievement.house_id || '';

                            const form = document.getElementById('editAchievementForm');
                            form.action = `/admin/achievements/${achievement.id}`;

                            const preview = document.getElementById('editPreview');
                            if (achievement.image) {
                                const imageUrl = achievement.image.startsWith('http')
                                    ? achievement.image
                                    : `/storage/${achievement.image}`;
                                preview.innerHTML = `<img src="${imageUrl}" class="w-full h-full object-cover rounded-xl shadow">`;
                            } else {
                                preview.innerHTML = `<i class='fas fa-trophy text-5xl opacity-50'></i>`;
                            }
                            document.getElementById('editImageModal').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('editPreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-xl shadow">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `<i class='fas fa-trophy text-5xl opacity-50'></i>`;
    }
});
                        }

                        function closeEditAchievementModal() {
                            const modal = document.getElementById('editAchievementModal');
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }

                        async function submitEditAchievement(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        });

        if (response.ok) {
            closeEditAchievementModal();

            
            const alert = document.createElement('div');
            alert.className = "fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded-xl shadow-lg z-[9999]";
            alert.innerHTML = `<i class='fas fa-check-circle mr-2'></i> Achievement updated successfully!`;
            document.body.appendChild(alert);

            setTimeout(() => alert.remove(), 4000);

            
            setTimeout(() => location.reload(), 800);
        } else {
            alert('Failed to update achievement.');
        }
    } catch (error) {
        console.error(error);
        alert('Error updating achievement.');
    }
}

                    </script>
                </div>
            </div>


        {{-- Students Table + Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Students Table (2/3) --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm p-6"
                x-data="{
                    students: {{ $students->map(function($student) {
                        return [
                            'id' => $student->id,
                            'student_code' => ($student->year < now()->year - 6 ? 'ALU-' : 'STU-') . $student->id,
                            'name' => $student->name,
                            'year' => $student->year,
                            'birth_date' => $student->birth_date,
                            'photo' => $student->photo,
                        ];
                    })->toJson() }},
                    limit: 5,
                    get visibleStudents() { return this.limit === 'all' ? this.students : this.students.slice(0,this.limit); }
                }">

                <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-3">
                    <h2 class="text-xl font-bold text-gray-800">Students in {{ $house->name }}</h2>

                    <div class="flex flex-col sm:flex-row gap-2 md:gap-3 items-start md:items-center w-full md:w-auto">
                        {{-- Status --}}
                        <form method="GET" action="{{ route('admin.houses.edit', $house->id) }}" class="flex w-full sm:w-auto">
                            <select name="status" onchange="this.form.submit()"
                                    class="w-full sm:w-auto border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-[#425d9e]">
                                <option value="active" {{ $filter==='active'?'selected':'' }}>Active</option>
                                <option value="alumni" {{ $filter==='alumni'?'selected':'' }}>Alumni</option>
                            </select>
                        </form>

                        {{-- Limit --}}
                        <div class="flex items-center gap-1 w-full sm:w-auto">
                            <label class="text-sm text-gray-600 hidden sm:block">Show:</label>
                            <select x-model="limit" @change="limit = $event.target.value==='all'?'all':parseInt($event.target.value)"
                                    class="w-full sm:w-auto border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-[#425d9e]">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="all">View all</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm text-gray-700 rounded-xl overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="py-2 px-4 border">ID</th>
                                <th class="py-2 px-4 border">Photo</th>
                                <th class="py-2 px-4 border">Name</th>
                                <th class="py-2 px-4 border">Year</th>
                                <th class="py-2 px-4 border">Birth Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="student in visibleStudents" :key="student.id">
                                <tr class="hover:bg-gray-50 transition" x-transition>
                                    <td class="py-2 px-4 border text-center" x-text="student.student_code"></td>
                                    <td class="py-2 px-4 border text-center">
                                        <img :src="student.photo ? '/storage/' + student.photo : '/images/icons/default.png'" 
                                            class="w-8 h-10 object-cover rounded-lg mx-auto">
                                    </td>
                                    <td class="py-2 px-4 border text-center" x-text="student.name"></td>
                                    <td class="py-2 px-4 border text-center" x-text="student.year"></td>
                                    <td class="py-2 px-4 border text-center" x-text="student.birth_date ?? '-'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            
            {{-- Student Overview --}}
            <div x-data="studentOverview()" x-init="init()" class="mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Student Overview</h2>

                <div class="flex items-center justify-between mb-4">
                    {{-- Year Select --}}
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Select Year</label>
                        <select x-model="selectedYear"
                                class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-[#425d9e]">
                            <template x-for="yearData in studentsPerYear" :key="yearData.year">
                                <option x-text="yearData.year" :value="yearData.year"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Total Students --}}
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Students</p>
                        <p class="text-lg font-semibold text-[#425d9e]" x-text="total"></p>
                    </div>
                </div>

                {{-- Download Button --}}
                <div class="text-right">
                    <button @click="openModal = true"
                        class="px-5 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
                        Download Summary
                    </button>
                </div>

                {{-- Modal --}}
                <template x-if="openModal">
                    <div class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
                        <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Download Student Summary</h3>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="block text-sm text-gray-600 mb-1">Select Status</label>
                                <select x-model="status" @change="updateYears()"
                                        class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-[#425d9e]">
                                    <option value="active">Active Students</option>
                                    <option value="alumni">Alumni</option>
                                </select>
                            </div>

                            {{-- Year Range --}}
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">From Year</label>
                                    <select x-model="startYear"
                                            class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-[#425d9e]">
                                        <template x-for="year in availableYears" :key="'start-' + year">
                                            <option x-text="year" :value="year"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 mb-1">To Year</label>
                                    <select x-model="endYear"
                                            class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-[#425d9e]">
                                        <template x-for="year in availableYears" :key="'end-' + year">
                                            <option x-text="year" :value="year"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            {{-- Single Year --}}
                            <div class="flex items-center gap-2 mb-3">
                                <input type="checkbox" x-model="singleYear"
                                    class="w-4 h-4 text-[#425d9e] border-gray-300 rounded focus:ring-[#425d9e]">
                                <label class="text-sm text-gray-700">Summarize this year only</label>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button @click="openModal = false" class="px-4 py-2 bg-gray-300 rounded-xl hover:bg-gray-400 transition">Cancel</button>
                                <button @click="downloadSummary" class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">Download PDF</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <script>
            function studentOverview() {
                return {
                    openModal: false,  // modal download
                    selectedYear: '{{ $studentsPerYear->first()->year ?? '' }}',
                    studentsPerYear: @json($studentsPerYear), // [{ year: 2025, total: 20 }, ...]
                    status: 'active',
                    singleYear: false,
                    startYear: '',
                    endYear: '',
                    availableYears: [],

                    // total students di tahun yg dipilih
                    get total() {
                        const yearData = this.studentsPerYear.find(y => y.year == this.selectedYear);
                        return yearData ? yearData.total : 0;
                    },

                    // update dropdown tahun
                    updateYears() {
                        const currentYear = new Date().getFullYear();
                        this.availableYears = [];
                        if (this.status === 'active') {
                            for (let y = currentYear; y >= currentYear - 6; y--) this.availableYears.push(y);
                        } else {
                            for (let y = currentYear - 7; y >= currentYear - 30; y--) this.availableYears.push(y);
                        }

                        this.startYear = this.availableYears[0];
                        this.endYear = this.availableYears[this.availableYears.length - 1];
                    },

                    // download PDF
                    downloadSummary() {
                        this.openModal = false;
                        const yearStart = this.singleYear ? this.startYear : this.startYear;
                        const yearEnd = this.singleYear ? this.startYear : this.endYear;
                        const url = `/admin/houses/{{ $house->id }}/summary/download?status=${this.status}&start_year=${yearStart}&end_year=${yearEnd}`;
                        window.open(url, '_blank');
                    },

                    init() {
                        this.updateYears();
                    }
                }
            }
            </script>


    </div>


    {{-- Professors --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Professors in {{ $house->name }}</h2>
            <a href="{{ route('admin.professors.index') }}"class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition">
            manage professors</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm text-gray-700 rounded-xl overflow-hidden">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border text-left">ID</th>
                        <th class="py-2 px-4 border text-left">Name</th>
                        <th class="py-2 px-4 border text-left">Position</th>
                        <th class="py-2 px-4 border text-left">Subject</th>
                        <th class="py-2 px-4 border text-left">House</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($house->professors as $prof)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-2 px-4 border">{{ $prof->id }}</td>
                            <td class="py-2 px-4 border">{{ $prof->name }}</td>
                            <td class="py-2 px-4 border">{{ $prof->position ?? '-' }}</td>
                            <td class="py-2 px-4 border">{{ $prof->subject ?? '-' }}</td>
                            <td class="py-2 px-4 border">{{ $prof->house->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">No professors yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
