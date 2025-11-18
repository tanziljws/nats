@extends('layouts.app')

@section('content')
<div x-data="achievementPage()" x-cloak class="pt-28 pb-20 px-4 sm:px-6 lg:px-8">

    <div class="max-w-7xl mx-auto transition-all duration-700"
         :class="pageLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-serif font-bold tracking-tight text-gray-900 mb-2 transition-all duration-500">
                Achievements
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base md:text-lg leading-relaxed">
                Browse all student achievements or filter by house to celebrate their magical accomplishments.
            </p>
        </div>

        {{-- Shared SVG gradient defs (match Facilities) --}}
        <svg width="0" height="0">
            <defs>
                <linearGradient id="catGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#b03535"/>
                    <stop offset="50%" stop-color="#3c5e5e"/>
                    <stop offset="100%" stop-color="#425d9e"/>
                </linearGradient>
            </defs>
        </svg>

        {{-- Category Selector --}}
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4 md:gap-6 mb-8 text-gray-700 font-semibold">
            <template x-for="cat in categories" :key="cat.id">
                <button @click="selectCategory(cat.id)" type="button"
                        class="relative px-3 py-1 hover:text-[#425d9e] transition-all duration-300 text-sm sm:text-base md:text-base"
                        :class="selectedCategory === cat.id ? 'text-[#425d9e] font-bold' : ''">
                    <span x-text="cat.name"></span>
                    <svg x-show="selectedCategory === cat.id"
                         x-transition
                         class="absolute bottom-0 left-0 w-full h-1 mt-1"
                         viewBox="0 0 100 4" preserveAspectRatio="none">
                        <rect width="100" height="4" rx="2" fill="url(#catGradient)"></rect>
                    </svg>
                </button>
            </template>
        </div>

        {{-- Achievement Cards --}}
        <div id="achievementGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <template x-for="(ach, idx) in paginatedAchievements" :key="ach.id">
                <div class="group bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer hover:shadow-2xl transition-all duration-300"
                     @click="openModal(idx)">
                    
                    <div class="relative h-48 overflow-hidden">
                        <img :src="ach.image ? `/storage/${ach.image}` : '/images/placeholder.jpg'" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             :alt="ach.title">

                        <!-- House Badge solid dark pill like homepage -->
                        <span class="absolute top-3 left-3 z-10 inline-flex items-center px-2 py-1 rounded-full bg-black/80 text-white text-xs sm:text-sm font-semibold shadow">
                            <span x-text="ach.house || 'General'"></span>
                        </span>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <div class="p-4 flex flex-col">
                        <div class="flex-1">
                            <div class="text-sm text-gray-500 mb-1" x-text="ach.date"></div>
                            <h3 class="text-lg font-semibold font-serif text-gray-900" x-text="ach.title"></h3>
                            <p class="text-gray-600 text-sm mt-1" x-text="ach.description"></p>
                        </div>
                        <!-- No counters on cards as requested -->
                    </div>
                </div>
            </template>
        </div>

        {{-- Pagination (admin style) --}}
        <div class="flex justify-center mt-8">
            <ul class="inline-flex items-center gap-1">
                <!-- Prev -->
                <li :class="{ 'opacity-40': currentPage === 1 }">
                    <button @click="currentPage = Math.max(1, currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition disabled:cursor-default">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                </li>

                <!-- Pages -->
                <template x-for="page in totalPages" :key="page">
                    <li>
                        <template x-if="currentPage === page">
                            <span aria-current="page"
                                  class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-white shadow border border-transparent focus:outline-none focus:ring-2"
                                  style="background-color:#425d9e;box-shadow:0 1px 3px rgba(0,0,0,.1),0 1px 2px rgba(0,0,0,.06);">
                                <span x-text="page"></span>
                            </span>
                        </template>
                        <template x-if="currentPage !== page">
                            <button @click="currentPage = page"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-sm font-semibold transition border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300/60">
                                <span x-text="page"></span>
                            </button>
                        </template>
                    </li>
                </template>

                <!-- Next -->
                <li :class="{ 'opacity-40': currentPage === totalPages }">
                    <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition disabled:cursor-default">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    {{-- Modal --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative w-full max-w-6xl h-[85vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row">

            <div class="md:w-3/5 w-full bg-black flex items-center justify-center relative">
                <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-6">
                    <h3 class="text-2xl md:text-4xl font-bold font-serif text-white drop-shadow-lg leading-tight mb-1" x-text="currentAchievement.title"></h3>
                    <p class="text-xs md:text-sm text-gray-300" x-text="currentAchievement.date"></p>
                    <p class="text-gray-200 mt-2" x-text="currentAchievement.description"></p>
                </div>
                <button @click="prevAchievement" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-white/20 hover:bg-white/40 text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <img :src="currentAchievement.image ? `/storage/${currentAchievement.image}` : '/images/placeholder.jpg'" 
                     class="max-h-[80vh] max-w-full object-contain transition-all duration-500"
                     :alt="currentAchievement.title" />

                <button @click="nextAchievement" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-white/20 hover:bg-white/40 text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="md:w-2/5 w-full p-5 flex flex-col">

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-bold font-serif text-gray-900" x-text="currentAchievement.title"></h3>
                        <p class="text-sm text-gray-600 mt-1" x-text="currentAchievement.description"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="currentAchievement.date"></p>
                    </div>

                    {{-- Like --}}
                    <button @click="toggleLike()" class="p-2 rounded-full hover:bg-gray-100 transition" :class="liked ? 'text-red-600' : 'text-gray-700'">
                        <svg x-show="!liked" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <svg x-show="liked" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <span x-text="likeCount"></span>
                    </span>
                </div>

                {{-- Comments --}}
                <div class="border-t mt-3 pt-3 flex-1 overflow-y-auto space-y-3">
                    <template x-if="comments.length === 0">
                        <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
                    </template>
                    <template x-for="c in comments" :key="c.id">
                        <div class="text-sm">
                            <strong x-text="c.name || 'Anonymous'" class="text-gray-800"></strong>
                            <p x-text="c.content" class="text-gray-700"></p>
                        </div>
                    </template>
                </div>

                {{-- Add Comment --}}
                <form @submit.prevent="postComment()" class="mt-3 flex gap-2">
                    <input type="text" x-model="newComment" placeholder="Write a comment..."
                        class="flex-1 px-3 py-2 rounded-2xl border border-gray-200 focus:outline-none" required>
                    <button type="submit" class="relative inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-white font-serif shadow transition hover:scale-105 overflow-hidden">
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 40" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="send-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#b03535" />
                                    <stop offset="50%" stop-color="#3c5e5e" />
                                    <stop offset="100%" stop-color="#425d9e" />
                                </linearGradient>
                            </defs>
                            <rect x="0" y="0" width="100" height="40" rx="16" ry="16" fill="url(#send-grad)" />
                        </svg>
                        <span class="relative">Send</span>
                    </button>
                </form>
            </div>

            <button @click="closeModal()" class="absolute top-3 right-3 md:hidden p-2 bg-white/80 rounded-full">
                <svg class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6L18 18M6 18L18 6" stroke="#000" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
</div>

<script>
function achievementPage() {
    return {
        pageLoaded: false,
        selectedCategory: 'all',
        modalOpen: false,
        modalIndex: 0,
        currentAchievement: {},
        achievements: @json($achievementsData),
        categories: [
            {id:'all', name:'All Achievements'},
            {id:'gryffindor', name:'Gryffindor'},
            {id:'slytherin', name:'Slytherin'},
            {id:'ravenclaw', name:'Ravenclaw'},
            {id:'hufflepuff', name:'Hufflepuff'},
        ],

        liked: false,
        likeCount: 0,
        viewCount: 0,
        comments: [],
        newComment: '',

        currentPage: 1,
        perPage: 6,

        init() {
            setTimeout(() => this.pageLoaded = true, 50);
            this.$watch('modalOpen', v => {
                document.body.style.overflow = v ? 'hidden' : '';
                if(!v){
                    history.replaceState(null,'','/guest/achievements');
                }
            });

            const params = new URLSearchParams(window.location.search);
            const modalId = params.get('modal');
            if(modalId) this.openModalById(parseInt(modalId));
        },

        get filteredAchievements() {
            if(this.selectedCategory === 'all') return this.achievements;
            return this.achievements.filter(a => a.house.toLowerCase() === this.selectedCategory);
        },
        get totalPages() {
            return Math.ceil(this.filteredAchievements.length / this.perPage);
        },
        get paginatedAchievements() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredAchievements.slice(start, start + this.perPage);
        },

        selectCategory(id) {
            this.selectedCategory = id;
            this.currentPage = 1;
        },

        openModal(idx) {
            this.modalIndex = idx;
            this.currentAchievement = this.filteredAchievements[idx];
            this.modalOpen = true;
            this.loadAchievementState();

            // Track view
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (token && this.currentAchievement?.id) {
                fetch(`/guest/achievements/${this.currentAchievement.id}/view`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `_token=${encodeURIComponent(token)}`,
                    credentials: 'same-origin'
                }).then(r=>r.json()).then(d=>{
                    if(d?.view_count !== undefined){
                        this.viewCount = d.view_count;
                        // also update in grid data
                        this.currentAchievement.view_count = d.view_count;
                    }
                }).catch(()=>{});
            }

            const id = this.currentAchievement.id;
            const params = new URLSearchParams(window.location.search);
            params.set('modal', id);
            history.replaceState(null, '', `/guest/achievements?${params.toString()}`);
        },

        openModalById(id){
            const idx = this.filteredAchievements.findIndex(a=>a.id===id);
            if(idx!==-1) this.openModal(idx);
        },

        closeModal() { this.modalOpen = false; },

        nextAchievement() {
            if (!this.filteredAchievements.length) return;
            this.modalIndex = (this.modalIndex + 1) % this.filteredAchievements.length;
            this.openModal(this.modalIndex);
        },

        prevAchievement() {
            if (!this.filteredAchievements.length) return;
            this.modalIndex = (this.modalIndex - 1 + this.filteredAchievements.length) % this.filteredAchievements.length;
            this.openModal(this.modalIndex);
        },

        houseColors(house) {
            const colors = {
                'Gryffindor': { from: '#5c0c0c', to: '#8a3333' },
                'Slytherin': { from: '#063015', to: '#336343' },
                'Ravenclaw': { from: '#182552', to: '#6e8ab5' },
                'Hufflepuff': { from: '#59510a', to: '#ab8e37' },
                'General': { from: '#6b7280', to: '#9ca3af' }
            };
            return colors[house] || colors['General'];
        },

        async loadAchievementState() {
            if(!this.currentAchievement?.id) return;

            try {
                const likeRes = await fetch(`/guest/achievements/${this.currentAchievement.id}/like-status`);
                if(likeRes.ok){
                    const data = await likeRes.json();
                    this.liked = data.liked;
                    this.likeCount = data.like_count ?? 0;
                }
            } catch(e){
                console.error('Error loading like status:', e);
            }

            // initialize view count from current data
            this.viewCount = this.currentAchievement?.view_count ?? 0;

            try {
                const commentRes = await fetch(`/guest/achievements/${this.currentAchievement.id}/comments`);
                if(commentRes.ok){
                    const data = await commentRes.json();
                    this.comments = (data.comments ?? []).map(c => ({
                        id: c.id,
                        name: c.name || 'Guest',
                        content: c.content
                    }));
                }
            } catch(e){
                console.error('Error loading comments:', e);
            }
        },

        async toggleLike() {
            console.log('toggleLike called');
            if (!this.currentAchievement?.id) {
                console.error('No achievement ID found');
                return;
            }

            try {
                const url = `/guest/achievements/${this.currentAchievement.id}/like`;
                console.log('Making request to:', url);
                
                // Get CSRF token from meta tag
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!token) {
                    console.error('CSRF token not found');
                    return;
                }
                
                // Create form data to send as form-urlencoded
                const formData = new URLSearchParams();
                formData.append('_token', token);
                
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData.toString(),
                    credentials: 'same-origin'
                });
                
                console.log('Response status:', res.status);
                
                if (!res.ok) {
                    const errorData = await res.json().catch(() => ({}));
                    console.error('Error response:', errorData);
                    
                    if (res.status === 401) {
                        console.log('User not authenticated, redirecting to login');
                        window.location.href = `/user/login?redirect=${encodeURIComponent(window.location.href)}`;
                        return;
                    }
                    
                    if (res.status === 419) {
                        console.log('CSRF token mismatch, reloading page to get new token');
                        window.location.reload();
                        return;
                    }
                    
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const data = await res.json();
                console.log('Response data:', data);
                
                this.liked = data.liked;
                this.likeCount = data.like_count ?? 0;
                console.log('Like status updated - liked:', this.liked, 'count:', this.likeCount);

            } catch (error) {
                console.error('Error in toggleLike:', error);
                alert('Gagal memperbarui like. Silakan coba lagi.');
            }
        },

        async postComment() {
            if (!this.newComment.trim() || !this.currentAchievement?.id) return;

            try {
                const res = await fetch(`/guest/achievements/${this.currentAchievement.id}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: this.newComment })
                });
                const data = await res.json();

                if (!res.ok || data.error === 'unauthenticated') {
                    window.location.href = `/user/login?redirect=${encodeURIComponent(window.location.href)}`;
                    return;
                }

                if (data.success) {
                    const c = data.comment;
                    this.comments.push({ id: c.id, name: c.name || 'Guest', content: c.content });
                    this.newComment = '';
                }

            } catch (e) {
                console.error(e);
            }
        }

    }
}
</script>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
