@extends('layouts.manage')

@section('title', 'Students Management')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Students', 'route' => null ],
    ];
@endphp

@section('content')
<div class="text-center mb-12">
    <!-- Header -->
    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] bg-clip-text text-transparent">
        Students Management
    </h1>
    <p class="text-gray-500 mt-2">Manage students and assign them to houses</p>
  </div>

  <!-- Filters & Total -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6 px-6 lg:px-10">
      <div class="flex gap-2 w-full md:w-auto">
          <input id="stu-search" type="text" value="{{ request('search') ?? '' }}" placeholder="Search name or code..." autocomplete="off"
                 class="flex-grow md:flex-grow-0 md:w-80 h-11 px-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3c5e5e]">
          <select id="stu-house" class="h-11 px-3 border border-gray-300 rounded-xl text-sm">
              <option value="">All Houses</option>
              @foreach(($houses ?? []) as $h)
                  <option value="{{ $h->id }}" {{ (string)request('house_id') === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
              @endforeach
          </select>
          <select id="stu-status" class="h-11 px-3 border border-gray-300 rounded-xl text-sm">
              <option value="">All Students</option>
              <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
              <option value="alumni" {{ request('status') === 'alumni' ? 'selected' : '' }}>Alumni</option>
          </select>
          @if(request('search') || request('house_id') || request('status'))
              <a href="{{ route('admin.students.index') }}" class="h-11 px-4 rounded-xl border text-sm text-gray-700 flex items-center">Reset</a>
          @endif
      </div>
      <a href="{{ route('admin.students.create') }}"
         class="px-4 py-2 bg-gradient-to-r from-[#b03535] via-[#3c5e5e] to-[#425d9e] text-white rounded-xl shadow hover:opacity-90 transition whitespace-nowrap">
         + Add Student
      </a>
  </div>

  <!-- Students Table -->
  <div class="px-6 lg:px-10">
      <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
          <table class="w-full text-left">
              <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
              <tr>
                  <th class="py-3 px-4">No</th>
                  <th class="py-3 px-4">ID</th>
                  <th class="py-3 px-4">Photo</th>
                  <th class="py-3 px-4">Name</th>
                  <th class="py-3 px-4">Birth Date</th>
                  <th class="py-3 px-4">Year</th>
                  <th class="py-3 px-4">House</th>
                  <th class="py-3 px-4 text-right">Actions</th>
              </tr>
              </thead>
              <tbody id="students-tbody">
              @forelse($students as $index => $student)
                  <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                      <td class="py-3 px-4 text-gray-500">{{ ($students->currentPage() - 1) * $students->perPage() + $index + 1 }}</td>
                      <td class="py-3 px-4 font-mono">
                          @php
                              $prefix = ($student->year < now()->year - 6) ? 'ALU-' : 'STU-';
                          @endphp
                          {{ $prefix . $student->id }}
                      </td>
                      <td class="py-3 px-4">
                          @if($student->photo)
                              <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="w-10 h-12 object-cover rounded-xl border border-gray-200 shadow-sm">
                          @else
                              <div class="w-10 h-12 flex items-center justify-center bg-gray-100 rounded-xl border border-gray-200 text-gray-400 text-xs">No Photo</div>
                          @endif
                      </td>
                      <td class="py-3 px-4 font-medium text-gray-800">{{ $student->name }}</td>
                      <td class="py-3 px-4">{{ $student->birth_date ?? '-' }}</td>
                      <td class="py-3 px-4">{{ $student->year ?? '-' }}</td>
                      <td class="py-3 px-4">{{ $student->house?->name ?? '-' }}</td>
                      <td class="py-3 px-4 text-right flex justify-end gap-3">
                          <a href="{{ route('admin.students.edit', $student->id) }}"
                             class="text-yellow-500 hover:text-yellow-600 flex items-center justify-center" title="Edit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M3 21h18" />
                                  <path d="M7 17v-4l10 -10l4 4l-10 10h-4" />
                                  <path d="M14 6l4 4" />
                                  <path d="M14 6l4 4L21 7L17 3Z" fill="currentColor" fill-opacity="0.3" />
                              </svg>
                          </a>
                          <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?')">
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
                      <td colspan="8" class="py-4 text-center text-gray-500">No students available.</td>
                  </tr>
              @endforelse
              </tbody>
          </table>

      </div>
      <div id="students-pagination" class="mt-6 flex justify-center pb-4">
          {{ $students->links('vendor.pagination.clean') }}
      </div>
  </div>

  <script>
      (function(){
          const search = document.getElementById('stu-search');
          const house = document.getElementById('stu-house');
          const status = document.getElementById('stu-status');
          const tbody = document.getElementById('students-tbody');
          const pager = document.getElementById('students-pagination');
          const baseUrl = '{{ route('admin.students.index') }}';
          let t;

          function buildUrl(pageUrl){
              const url = new URL(pageUrl || baseUrl, window.location.origin);
              const s = search.value.trim();
              const h = house.value;
              const st = status.value;
              if(s) url.searchParams.set('search', s); else url.searchParams.delete('search');
              if(h) url.searchParams.set('house_id', h); else url.searchParams.delete('house_id');
              if(st) url.searchParams.set('status', st); else url.searchParams.delete('status');
              return url.toString();
          }

          async function refresh(pageUrl){
              const url = buildUrl(pageUrl);
              const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
              const html = await res.text();
              const doc = new DOMParser().parseFromString(html, 'text/html');
              const newBody = doc.querySelector('#students-tbody');
              const newPager = doc.querySelector('#students-pagination');
              if(newBody) tbody.innerHTML = newBody.innerHTML;
              if(newPager) pager.innerHTML = newPager.innerHTML;
              pager.querySelectorAll('a').forEach(a => {
                  a.addEventListener('click', (e) => { e.preventDefault(); refresh(a.href); });
              });
          }

          function debounced(){ clearTimeout(t); t = setTimeout(()=>refresh(), 300); }
          search.addEventListener('input', debounced);
          house.addEventListener('change', ()=>refresh());
          status.addEventListener('change', ()=>refresh());
          pager.querySelectorAll('a').forEach(a => {
              a.addEventListener('click', (e) => { e.preventDefault(); refresh(a.href); });
          });
      })();
  </script>
@endsection
