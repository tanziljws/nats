@extends('layouts.app')

@section('content')
<div x-data="galleryPage()" x-cloak class="pt-28 pb-20 px-6">
    <div class="max-w-7xl mx-auto transition-all duration-700"
         :class="pageLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-serif font-bold tracking-tight text-gray-900 mb-2">
                Our Facilities
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Explore our school's facilities through a visual gallery — browse categories, open photos, like, comment and swipe.
            </p>
        </div>

        {{-- SVG GRADIENT (harus ada sekali di halaman) --}}
        <svg width="0" height="0">
            <defs>
                <linearGradient id="catGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#b03535"/>
                    <stop offset="50%" stop-color="#3c5e5e"/>
                    <stop offset="100%" stop-color="#425d9e"/>
                </linearGradient>
            </defs>
        </svg>

        {{-- Category selector (match Achievements: centered, wraps) --}}
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4 md:gap-6 mb-8 text-gray-700 font-semibold" aria-label="Facility categories">
            <template x-for="cat in categories" :key="cat.id">
                <button @click="selectCategory(cat.id)" type="button"
                        class="relative px-3 py-1 hover:text-[#425d9e] transition-all duration-300"
                        :class="selectedCategory === cat.id ? 'text-[#425d9e] font-bold' : ''">
                    
                    <span x-text="cat.name"></span>

                    {{-- SVG Underline --}}
                    <svg x-show="selectedCategory === cat.id"
                        x-transition
                        class="absolute bottom-0 left-0 w-full h-1 mt-1"
                        viewBox="0 0 100 4" preserveAspectRatio="none">
                        <rect width="100" height="4" rx="2" fill="url(#catGradient)"></rect>
                    </svg>

                </button>
            </template>
        </div>

        {{-- Category Grid --}}
        <div x-show="!selectedCategory"
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <template x-for="cat in categories" :key="cat.id">
                <div @click="selectCategory(cat.id)"
                     class="cursor-pointer group relative rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-400 hover:scale-105">
                    <img :src="cat.cover ? `/storage/${cat.cover}` : `https://via.placeholder.com/800x600?text=${cat.name}`"
                         class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105"
                         :alt="cat.name">
                    <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/60 to-transparent p-4">
                        <h2 class="text-lg md:text-xl font-serif font-semibold text-white" x-text="cat.name"></h2>
                    </div>
                </div>
            </template>
        </div>

        {{-- Photos Grid --}}
        <div x-show="selectedCategory"
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 translate-y-6"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-6"
             class="space-y-6">
            
            {{-- Photos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template x-for="(photo, idx) in selectedPhotos" :key="photo.id">
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white">
                        <div class="relative">
                            <img :src="`/storage/${photo.image}`" 
                                 class="w-full h-64 object-cover cursor-pointer transition-transform duration-300 hover:scale-[1.02]"
                                 @click="openModal(idx)">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-800 text-sm truncate" x-text="photo.name"></h3>
                            <p class="text-xs text-gray-500 mt-1" x-text="photo.caption"></p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Back --}}
            <div class="mt-6 text-center">
                <button @click="selectedCategory = null"
                        class="px-5 py-2 rounded-full font-serif text-gray-800 hover:text-[#425d9e] transition">
                    ← Back to Categories
                </button>

            </div>
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

            {{-- LEFT --}}
            <div class="md:w-3/5 w-full bg-black flex items-center justify-center relative">
                <button @click="prevPhoto" 
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-white/20 hover:bg-white/40 text-white transition">
                    ‹
                </button>

                <template x-for="(p,i) in selectedPhotos" :key="p.id">
                    <img x-show="modalIndex === i" 
                         :src="`/storage/${p.image}`" 
                         class="max-h-[80vh] max-w-full object-contain transition-all duration-500"
                         :alt="p.name" />
                </template>

                <button @click="nextPhoto" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-white/20 hover:bg-white/40 text-white transition">
                    ›
                </button>
            </div>

            {{-- RIGHT --}}
            <div class="md:w-2/5 w-full p-5 flex flex-col">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-xl font-bold font-serif text-gray-900" x-text="currentPhoto.name"></h3>
                        <p class="text-sm text-gray-600 font-sans mt-1" x-text="currentPhoto.description"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="currentPhoto.caption"></p>
                    </div>

                    {{-- Like button --}}
                    <button @click="toggleLike()" class="like-button p-2 rounded-full hover:bg-gray-100"
                            :class="liked ? 'text-red-600' : 'text-gray-700'">
                        <svg x-show="!liked" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <svg x-show="liked" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                </div>

                <span class="like-count text-sm text-gray-600 mt-2" x-text="likeCount + ' likes'"></span>

                {{-- Comments --}}
                <div class="border-t mt-3 pt-3 flex-1 overflow-y-auto space-y-3">
                    <template x-if="comments.length === 0">
                        <p class="text-sm text-gray-500">No comments yet. Be the first to comment!</p>
                    </template>
                    <template x-for="c in comments" :key="c.id">
                        <div class="text-sm">
                            <strong x-text="c.name" class="text-gray-800"></strong>
                            <p x-text="c.content" class="text-gray-700"></p>
                        </div>
                    </template>
                </div>

                {{-- Add Comment --}}
                <form @submit.prevent="postComment()" class="mt-3 flex gap-2">
                    <input type="text" x-model="newComment" placeholder="Write a comment..."
                        class="flex-1 px-3 py-2 rounded-2xl border border-gray-200 focus:outline-none" required>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-white font-serif shadow transition hover:scale-105"
                            style="background: linear-gradient(90deg, #b03535, #3c5e5e, #425d9e);">
                        Send
                    </button>
                </form>
            </div>

            {{-- Close --}}
            <button @click="closeModal()" class="absolute top-3 right-3 md:hidden p-2 bg-white/80 rounded-full">
                <svg class="w-5 h-5 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 6L18 18M6 18L18 6" stroke="#000" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function galleryPage() {
    return {
        pageLoaded: false,
        categories: @json($categoriesData),
        selectedCategory: null,
        modalOpen: false,
        modalIndex: 0,
        currentPhoto: {},
        comments: [],
        newComment: '',
        liked: false,
        likeCount: 0,

        init() {
            setTimeout(() => this.pageLoaded = true, 80);
            this.$watch('modalOpen', v => document.body.style.overflow = v ? 'hidden' : '');
        },

        get selectedPhotos() {
            if (!this.selectedCategory) return [];
            const cat = this.categories.find(c => c.id === this.selectedCategory);
            return cat ? cat.photos : [];
        },
        selectCategory(id) {
            this.selectedCategory = id;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        openModal(i) {
            const photo = this.selectedPhotos[i];
            if(!photo) return;

            this.modalIndex = i;
            this.currentPhoto = {
                id: photo.id,
                name: photo.name,
                caption: photo.caption || '',
                description: photo.description || '',
                image: photo.image,
            };
            this.modalOpen = true;
            this.loadPhotoState();
        },
        closeModal() {
            this.modalOpen = false;
        },
        nextPhoto() {
            if (!this.selectedPhotos.length) return;
            this.modalIndex = (this.modalIndex + 1) % this.selectedPhotos.length;
            this.openModal(this.modalIndex);
        },
        prevPhoto() {
            if (!this.selectedPhotos.length) return;
            this.modalIndex = (this.modalIndex - 1 + this.selectedPhotos.length) % this.selectedPhotos.length;
            this.openModal(this.modalIndex);
        },

        async loadPhotoState() {
            const id = this.currentPhoto?.id;
            if (!id) return;

            try {
                const likeRes = await fetch(`/guest/facilities/photos/${id}/like-status`);
                if (likeRes.ok) {
                    const data = await likeRes.json();
                    this.liked = data.liked;
                    this.likeCount = data.like_count ?? 0;
                }
            } catch (e) { console.error(e); }

            try {
                const commentRes = await fetch(`/guest/facilities/photos/${id}/comments`);
                if (commentRes.ok) {
                    const data = await commentRes.json();
                    this.comments = (data.comments || []).map(c => ({
                        id: c.id,
                        name: c.name || 'Guest',
                        content: c.content
                    }));
                }
            } catch (e) { console.error(e); }
        },

        async toggleLike() {
            const id = this.currentPhoto?.id;
            if (!id) return;

            try {
                const url = `/guest/facilities/photos/${id}/like`;

                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!token) return;

                const headers = new Headers();
                headers.append('X-CSRF-TOKEN', token);
                headers.append('Accept', 'application/json');
                headers.append('X-Requested-With', 'XMLHttpRequest');

                const formData = new FormData();
                formData.append('_token', token);

                const res = await fetch(url, {
                    method: 'POST',
                    headers,
                    body: formData,
                    credentials: 'same-origin'
                });

                if (res.status === 401) {
                    const redirectUrl = encodeURIComponent(window.location.href);
                    window.location.href = `/user/login?redirect=${redirectUrl}`;
                    return;
                }

                if (res.status === 419) {
                    window.location.reload();
                    return;
                }

                if (!res.ok) {
                    alert('Failed to update like. Try again.');
                    return;
                }

                const data = await res.json();
                this.liked = !!data.liked;
                this.likeCount = data.like_count ?? this.likeCount;

            } catch (e) { console.error(e); }
        },

        async postComment() {
            if (!this.newComment.trim()) return;

            const id = this.currentPhoto?.id;
            if (!id) return;

            try {
                const res = await fetch(`/guest/facilities/photos/${id}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: this.newComment })
                });

                if (res.status === 401) {
                    window.location.href = `/user/login?redirect=${encodeURIComponent(window.location.href)}`;
                    return;
                }

                if (res.ok) {
                    await this.loadPhotoState();
                    this.newComment = '';
                }
            } catch (e) { console.error(e); }
        }
    }
}
</script>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
