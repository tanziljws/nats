
{{-- Achievements Section --}}
<section id="achievements" class="relative py-20">
    {{-- Background Hero --}}
    <div class="absolute inset-0">
        <img src="{{ $profile && $profile->hero_image ? asset('storage/' . $profile->hero_image) : 'https://picsum.photos/1600/900?blur' }}" 
             alt="Achievements Background" 
             class="w-full h-full object-cover brightness-75">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-16 text-gray-900 relative z-10">
            <h2 class="text-4xl md:text-5xl font-serif font-bold tracking-tight text-white">Achievements</h2>
            <div class="w-24 h-1 mx-auto mt-4">
                <svg width="100%" height="100%" viewBox="0 0 96 4" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" style="stop-color:#b03535" />
                            <stop offset="50%" style="stop-color:#3c5e5e" />
                            <stop offset="100%" style="stop-color:#425d9e" />
                        </linearGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#gradient)" rx="2" ry="2" />
                </svg>
            </div>
            <p class="mt-4 text-gray-200 max-w-2xl mx-auto leading-relaxed">
                Proud moments and recognitions from our school community
            </p>
        </div>

        {{-- Grid Achievements, latest 3 --}}
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($achievements->take(3) as $achievement)
                @php
                    $houseColors = [
                        'Gryffindor' => ['from' => '#5c0c0c', 'to' => '#8a3333'],
                        'Slytherin' => ['from' => '#063015', 'to' => '#336343'],
                        'Ravenclaw' => ['from' => '#182552', 'to' => '#6e8ab5'],
                        'Hufflepuff' => ['from' => '#59510a', 'to' => '#ab8e37'],
                    ];
                    $houseName = $achievement->house->name ?? null;
                    $gradientFrom = $houseName && isset($houseColors[$houseName]) ? $houseColors[$houseName]['from'] : '#888';
                    $gradientTo = $houseName && isset($houseColors[$houseName]) ? $houseColors[$houseName]['to'] : '#aaa';
                @endphp

                {{-- Card Link ke Index --}}
                <a href="{{ route('guest.achievements.index') }}?modal={{ $achievement->id }}" class="block cursor-pointer">
                    <article class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                        {{-- Image --}}
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $achievement->image ? asset('storage/' . $achievement->image) : '/images/placeholder.jpg' }}" 
                                 alt="{{ $achievement->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">

                            {{-- House Badge --}}
                            @if($houseName)
                                <span class="absolute top-3 left-3 px-2 py-1 text-xs font-semibold rounded-full text-white shadow-sm z-10"
                                      style="background: linear-gradient(90deg, {{ $gradientFrom }} 0%, {{ $gradientTo }} 100%);">
                                    {{ $houseName }}
                                </span>
                            @endif

                            {{-- Overlay hover --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex-1">
                                <div class="text-sm text-gray-500 mb-2">
                                    {{ \Carbon\Carbon::parse($achievement->date)->format('F j, Y') }}
                                </div>
                                <h3 class="text-xl font-serif font-semibold text-gray-900 mb-3 line-clamp-2">
                                    {{ $achievement->title }}
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                    {{ Str::limit($achievement->description, 120) }}
                                </p>
                            </div>
                        </div>
                    </article>
                </a>
            @endforeach
        </div>

        {{-- View More Button --}}
        <div class="mt-12 text-center relative z-10">
            <a href="{{ route('guest.achievements.index') }}"
               class="relative overflow-hidden group inline-block px-8 py-3 font-serif rounded-full
                      text-white tracking-wide shadow-md hover:shadow-lg transition-all duration-300
                      hover:scale-105"
               style="background: linear-gradient(90deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%); background-clip: padding-box;">
               <span class="relative z-10">View More</span>
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let currentAchievementUrl = "";

    function openAchievementModal(id, card = null){
        card = card || document.querySelector(`[data-id='${id}']`);
        if(!card) return;

        currentAchievementUrl = window.location.origin + '/guest/achievements?modal=' + id;

        document.getElementById('modalTitle').innerText = card.dataset.title;
        document.getElementById('modalMeta').innerText = `${card.dataset.writer} • ${new Date(card.dataset.date).toLocaleDateString()}`;
        document.getElementById('modalContent').innerText = card.dataset.description;
        document.getElementById('modalImage').src = card.dataset.image;
        document.getElementById('modalReadMore').href = `/guest/achievements?modal=${id}`;

        const modal = document.getElementById('achievementModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Update URL without reload
        const params = new URLSearchParams(window.location.search);
        params.set('modal', id);
        history.replaceState(null, '', `/guest?${params.toString()}`);
    }

    function closeAchievementModal(){
        const modal = document.getElementById('achievementModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // remove modal param
        const params = new URLSearchParams(window.location.search);
        params.delete('modal');
        history.replaceState(null, '', '/guest');
    }

    // Share / Copy
    function shareOnInstagram(){ window.open(`https://instagram.com/stories/create?url=${encodeURIComponent(currentAchievementUrl)}`,"_blank"); }
    function shareOnFacebook(){ window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentAchievementUrl)}`,"_blank"); }
    function shareOnX(){ window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(currentAchievementUrl)}&text=${encodeURIComponent("Check this out!")}`,"_blank"); }
    function shareOnWhatsApp(){ window.open(`https://wa.me/?text=${encodeURIComponent("Check this out!")} ${encodeURIComponent(currentAchievementUrl)}`,"_blank"); }
    function copyLink(){ navigator.clipboard.writeText(currentAchievementUrl); alert("Link copied to clipboard!"); }

    // Attach to global
    window.openAchievementModal = openAchievementModal;
    window.closeAchievementModal = closeAchievementModal;
    window.shareOnInstagram = shareOnInstagram;
    window.shareOnFacebook = shareOnFacebook;
    window.shareOnX = shareOnX;
    window.shareOnWhatsApp = shareOnWhatsApp;
    window.copyLink = copyLink;

    // Card click
    document.querySelectorAll('[data-id]').forEach(card => {
        card.addEventListener('click', e => {
            e.preventDefault();
            openAchievementModal(card.dataset.id, card);
        });
    });

    // Auto open from query param
    const params = new URLSearchParams(window.location.search);
    const modalId = params.get('modal');
    if(modalId){
        openAchievementModal(modalId);
    }
});
</script>
