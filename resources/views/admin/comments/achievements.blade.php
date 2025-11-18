@extends('layouts.manage')

@section('title', 'Achievement Comments')

@php
    $links = [
        ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['label' => 'Comments', 'route' => route('admin.comments.index')],
        ['label' => 'Achievements', 'route' => null],
    ];
@endphp

@section('content')
<section class="mb-16 px-6 lg:px-10">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold flex items-center gap-2">
            <span class="w-1.5 h-8 bg-gradient-to-b from-[#b03535] via-[#3c5e5e] to-[#425d9e] rounded-full"></span>
            Achievement Comments
        </h2>
        {{-- Filters: House and Achievement --}}
        <form method="GET" action="{{ route('admin.comments.achievements') }}" class="flex items-center gap-2">
            <select name="house_id" class="h-10 px-3 border border-gray-300 rounded-lg text-sm" onchange="this.form.submit()">
                <option value="">All Houses</option>
                @foreach(($houses ?? []) as $h)
                    <option value="{{ $h->id }}" {{ (string)($selectedHouseId ?? '') === (string)$h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                @endforeach
            </select>
            <select name="achievement_id" class="h-10 px-3 border border-gray-300 rounded-lg text-sm w-44" {{ empty($selectedHouseId) ? 'disabled' : '' }}>
                <option value="">All Achievements</option>
                @foreach(($achievementsList ?? []) as $a)
                    <option value="{{ $a->id }}" {{ (string)($selectedAchievementId ?? '') === (string)$a->id ? 'selected' : '' }}>
                        {{ Str::limit($a->title, 60) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="h-10 px-4 rounded-lg bg-gradient-to-r from-[#3c5e5e] to-[#425d9e] text-white text-sm">Filter</button>
            @if(!empty($selectedHouseId) || !empty($selectedAchievementId))
                <a href="{{ route('admin.comments.achievements') }}" class="h-10 px-3 rounded-lg border text-sm text-gray-700">Reset</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm flex items-start justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">✕</button>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-md border border-gray-200">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="py-3 px-4">Achievement</th>
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Comment</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($comments as $comment)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    {{-- Achievement --}}
                    <td class="py-3 px-4">
                        @if($comment->achievement)
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ $comment->achievement->image ? asset('storage/' . $comment->achievement->image) : 'https://via.placeholder.com/160x120?text=No+Image' }}" alt="{{ $comment->achievement->title }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-800">{{ Str::limit($comment->achievement->title, 80) }}</span>
                                    @if($comment->achievement->house?->name)
                                        @php
                                            $houseName = $comment->achievement->house->name;
                                            $houseGradients = [
                                                'Gryffindor' => ['#5c0c0c', '#8a3333'],
                                                'Slytherin' => ['#063015', '#336343'],
                                                'Ravenclaw' => ['#182552', '#6e8ab5'],
                                                'Hufflepuff' => ['#59510a', '#ab8e37'],
                                            ];
                                            $from = $houseGradients[$houseName][0] ?? '#888888';
                                            $to   = $houseGradients[$houseName][1] ?? '#aaaaaa';
                                        @endphp
                                        <span class="mt-1 inline-flex items-center justify-center h-6 w-20 text-white text-xs font-semibold rounded-full shadow-sm"
                                              style="background: linear-gradient(90deg, {{ $from }} 0%, {{ $to }} 100%);">
                                            {{ $houseName }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 italic">Achievement Deleted</span>
                        @endif
                    </td>

                    {{-- Name --}}
                    <td class="py-3 px-4">{{ $comment->name ?: 'Anonymous' }}</td>

                    {{-- Comment --}}
                    <td class="py-3 px-4 text-gray-700">
                        <span class="text-sm">{{ Str::limit($comment->content, 120) }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="py-3 px-4">
                        @if($comment->is_approved)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Approved</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Hidden</span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td class="py-3 px-4 text-gray-600 text-sm">
                        {{ $comment->created_at->format('d M Y H:i') }}
                    </td>

                    {{-- Actions --}}
                    <td class="py-3 px-4 text-right flex items-center justify-end gap-3">
                        {{-- Toggle Approval --}}
                        <button 
                            class="toggle-approval"
                            data-type="achievement"
                            data-id="{{ $comment->id }}"
                            data-approved="{{ $comment->is_approved }}"
                        >
                            @if($comment->is_approved)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 6h8a6 6 0 010 12H8a6 6 0 010-12z"/>
                                    <circle cx="16" cy="12" r="3" class="text-white" fill="white"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 6h8a6 6 0 010 12H8a6 6 0 010-12z"/>
                                    <circle cx="8" cy="12" r="3" class="text-white" fill="white"/>
                                </svg>
                            @endif
                        </button>

                        {{-- Delete --}}
                        <form action="{{ route('admin.comments.achievements.delete', $comment->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Delete this comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6V4h6v2m-7 4v8m4-8v8m4-8v8M5 6l1 14a2 2 0 002 2h8a2 2 0 002-2l1-14"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-500">No comments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $comments->onEachSide(1)->links('vendor.pagination.clean') }}
    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-approval').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const id = this.dataset.id;

            fetch('{{ route("admin.comments.toggle-approval") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ type, id })
            })
            .then(res => res.json())
            .then(data => { if (data.success) location.reload(); });
        });
    });
});
</script>
@endsection
