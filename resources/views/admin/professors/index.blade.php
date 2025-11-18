@extends('layouts.manage')

@section('title', 'Professors')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Edit ' , 'route' => route('admin.houses.index')],
        ['label' => 'Professors', 'route' => null],
    ];
@endphp

@section('content')
<div class="text-center mb-12">
    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
        Professors Management
    </h1>
    <p class="text-gray-500 mt-2">Manage professors and assign them to houses and subjects</p>
</div>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6 px-6 lg:px-10">
    <div class="flex gap-2 w-full md:w-auto">
        <input id="prof-search" type="text" value="{{ $searchTerm ?? '' }}" placeholder="Search name or subject..." autocomplete="off"
               class="flex-grow md:flex-grow-0 md:w-80 h-11 px-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3c5e5e]">
        <select id="prof-house" class="h-11 px-3 border border-gray-300 rounded-xl text-sm">
            <option value="">All Houses</option>
            @foreach(($houses ?? []) as $h)
                <option value="{{ $h->id }}" {{ (string)($selectedHouseId ?? '') === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
            @endforeach
        </select>
        @if(($searchTerm ?? '') || ($selectedHouseId ?? ''))
            <a href="{{ route('admin.professors.index') }}" class="h-11 px-4 rounded-xl border text-sm text-gray-700 flex items-center">Reset</a>
        @endif
    </div>
    <a href="{{ route('admin.professors.create') }}"
       class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition whitespace-nowrap">
       + Add Professor
    </a>
</div>

<div class="px-6 lg:px-10">
    <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Position</th>
                    <th class="py-3 px-4">Subject</th>
                    <th class="py-3 px-4">House</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="professors-tbody">
                @forelse($professors as $index => $prof)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-gray-500">{{ ($professors->currentPage() - 1) * $professors->perPage() + $index + 1 }}</td>
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $prof->name }}</td>
                        <td class="py-3 px-4">{{ $prof->position ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $prof->subject ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $prof->house?->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-right flex justify-end gap-3">
                            <a href="{{ route('admin.professors.edit', $prof->id) }}"
                               class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18" />
                                    <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                    <path d="M14 6l4 4" />
                                    <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                                </svg>
                            </a>

                            <form action="{{ route('admin.professors.destroy', $prof->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this professor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 flex items-center justify-center" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <td colspan="6" class="py-4 text-center text-gray-500">No professors available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div id="professors-pagination" class="mt-6 flex justify-end px-4 pb-4">
            {{ $professors->withQueryString()->links('vendor.pagination.clean') }}
        </div>
    </div>
</div>
<script>
    (function(){
        const search = document.getElementById('prof-search');
        const house = document.getElementById('prof-house');
        const tbody = document.getElementById('professors-tbody');
        const pager = document.getElementById('professors-pagination');
        const baseUrl = '{{ route('admin.professors.index') }}';
        let t;

        function buildUrl(pageUrl){
            const url = new URL(pageUrl || baseUrl, window.location.origin);
            const s = search.value.trim();
            const h = house.value;
            if(s) url.searchParams.set('search', s); else url.searchParams.delete('search');
            if(h) url.searchParams.set('house_id', h); else url.searchParams.delete('house_id');
            return url.toString();
        }

        async function refresh(pageUrl){
            const url = buildUrl(pageUrl);
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newBody = doc.querySelector('#professors-tbody');
            const newPager = doc.querySelector('#professors-pagination');
            if(newBody) tbody.innerHTML = newBody.innerHTML;
            if(newPager) pager.innerHTML = newPager.innerHTML;
            // Re-bind pagination clicks to use AJAX
            pager.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', (e) => { e.preventDefault(); refresh(a.href); });
            });
        }

        function debounced(){ clearTimeout(t); t = setTimeout(()=>refresh(), 300); }
        search.addEventListener('input', debounced);
        house.addEventListener('change', ()=>refresh());
        // Hijack initial pagination links
        pager.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', (e) => { e.preventDefault(); refresh(a.href); });
        });
    })();
</script>
@endsection
