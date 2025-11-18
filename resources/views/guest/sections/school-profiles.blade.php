{{-- Hogwarts Houses Section --}}
<section id="about" class="py-20 px-6 bg-[#f4f1ec]">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- About + Vision/Mission --}}
        <div class="lg:col-span-2" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 font-serif border-l-4 pl-4"
                style="border-image: linear-gradient(to right, #b03535, #3c5e5e, #425d9e) 1;">
                About the School
            </h2>
            <p class="text-gray-700 leading-relaxed text-lg mb-8">{{ $profile->about }}</p>

            <div class="grid md:grid-cols-2 gap-8 mb-10">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 font-serif border-l-4 pl-4"
                        style="border-image: linear-gradient(to right, #b03535, #3c5e5e, #425d9e) 1;">
                        Vision
                    </h3>
                    <p class="text-gray-700 text-lg leading-relaxed">{{ $profile->vision ?? 'Our vision description goes here.' }}</p>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 font-serif border-l-4 pl-4"
                        style="border-image: linear-gradient(to right, #b03535, #3c5e5e, #425d9e) 1;">
                        Mission
                    </h3>
                    <p class="text-gray-700 text-lg leading-relaxed">{{ $profile->mission ?? 'Our mission description goes here.' }}</p>
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 font-serif border-l-4 pl-4"
                style="border-image: linear-gradient(to right, #b03535, #3c5e5e, #425d9e) 1;">
                Location
            </h2>
            <div class="rounded-2xl overflow-hidden shadow-lg bg-gray-100">
                @if(!empty($profile->map_embed))
                    <div class="relative w-full" style="padding-top:56.25%">
                        <div class="absolute inset-0 [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0">
                            {!! $profile->map_embed !!}
                        </div>
                    </div>
                @else
                    <div class="relative w-full" style="padding-top:56.25%">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18..."
                                class="absolute inset-0 w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Hogwarts Houses --}}
    <div class="max-w-6xl mx-auto mt-12">
    <h2 class="text-3xl font-bold text-gray-900 font-serif border-l-4 pl-4 mb-6"
        style="border-image: linear-gradient(to right, #b03535, #3c5e5e, #425d9e) 1;">
        Hogwarts Houses
    </h2>

    {{-- Grid Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $houseColors = [
                'Gryffindor' => ['from' => '#120502', 'to' => '#a62d2d'],
                'Slytherin'  => ['from' => '#021205', 'to' => '#2f703a'],
                'Ravenclaw'  => ['from' => '#020a12', 'to' => '#3e6cb3'],
                'Hufflepuff' => ['from' => '#120502', 'to' => '#ab8e37'],
            ];
        @endphp

        @foreach($houseStats as $house)
            <a href="{{ route('guest.houses.index', ['house' => $house->id]) }}"
            class="group relative rounded-2xl overflow-hidden shadow-md cursor-pointer transition-transform duration-500 min-h-[340px] hover:scale-115"
            style="background: linear-gradient(to bottom, {{ $houseColors[$house->name]['from'] }}, {{ $houseColors[$house->name]['to'] }}); 
                    clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);"
                    onmouseover="this.style.transform='scale(1.05)';"
                    onmouseout="this.style.transform='scale(1)';">

    {{-- Banner Content --}}
    <div class="flex flex-col items-center text-white p-6 space-y-4">
        <img src="{{ asset('storage/' . $house->logo) }}" class="w-12 h-12 drop-shadow-lg">
        <h3 class="text-3xl font-serif">{{ $house->name }}</h3>

        <div class="gap-6 text-center pt-10 grid grid-cols-2">
            <div>
                <p class="font-serif text-sm text-white opacity-80">Students</p>
                <p class="text-3xl font-bold text-white drop-shadow-lg">{{ $house->students_last7years }}</p>
            </div>
            <div>
                <p class="font-serif text-sm text-white opacity-80">Alumni</p>
                <p class="text-3xl font-bold text-white drop-shadow-lg">{{ $house->total_alumni ?? 0 }}</p>
            </div>
        </div>
    </div>
</a>
@endforeach


    </div>
</div>

<div class="mt-10 transition-all duration-500 relative z-0">
        <a href="#history" class="block">
            <div class="pt-20 flex flex-col items-center hover:scale-110 transition-all duration-500">
                <p style="
                    background: linear-gradient(90deg, #7a1b1b 0%, #3b4f5f 50%, #243c7a 100%);
                    -webkit-background-clip: text;
                    background-clip: text;
                    -webkit-text-fill-color: transparent;
                    text-fill-color: transparent;
                    display: inline-block;"
                    class="text-4xl font-serif font-extrabold text-center">
                    Discover Our Legacy
                </p>
                <img src="{{ asset('storage/arrow.svg') }}" 
                    alt="Arrow" 
                    class="h-25 w-25 mt-2 transition-transform duration-300">
            </div>
        </a>
    </div>
    

    
</section>






{{-- History + Founders --}}
<div x-data="founderModal()" x-cloak>
    <section id="history" class="py-20 px-6 bg-[#f4f1ec] border-t border-gray-200 overflow-hidden">
        <div class="max-w-5xl mx-auto" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 font-serif border-l-4 border-[#3c5e5e] pl-4">Our History</h2>

            @if($profile->founded_year)
                <p class="text-gray-700 mb-4 text-lg italic">
                    Founded in <span class="font-semibold text-gray-900">{{ $profile->founded_year }}</span>
                </p>
            @endif

            <p class="text-gray-700 leading-relaxed mb-6 text-justify">
                {{ $profile->history ?? 'The school has a rich history dating back over a thousand years...' }}
            </p>

            {{-- Founders --}}
            @php
                $founderTextColors = [
                    'Godric Gryffindor' => '#300901', 
                    'Salazar Slytherin' => '#013006', 
                    'Rowena Ravenclaw'  => '#011130', 
                    'Helga Hufflepuff'  => '#302701', 
                ];
            @endphp

            <h2 class="inline-block text-3xl font-serif font-extrabold pt-4 text-center">
                The Four Founders of Hogwarts
            </h2>
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

            {{-- Grid Founder Cards --}}
            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-8 pt-8">
                @foreach ($founders as $founder)
                    @php $textColor = $founderTextColors[$founder->name] ?? '#3c5e5e'; @endphp
                    <div @click="openModal({{ $loop->index }})" class="cursor-pointer bg-white rounded-3xl overflow-hidden shadow-lg transition-transform duration-500 hover:scale-105">
                        <img src="{{ $founder->photo ? asset('storage/' . $founder->photo) : 'https://picsum.photos/300/300?random=' . $loop->index }}"
                             alt="{{ $founder->name }}"
                             class="w-full h-40 object-cover">
                        <div class="p-5 text-left">
                            <h3 class="font-semibold text-xl text-center" style="color: {{ $textColor }};">
                                {{ $founder->name }}
                            </h3>
                            <p class="text-gray-500 text-sm mt-1 italic text-left pt-4">
                                {{ 'Born in ' . $founder->birth_year }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Modal --}}
<div x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="close()">

    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl h-[360px] md:h-[420px] flex flex-col md:flex-row overflow-hidden relative">

        {{-- Photo --}}
        <div class="md:w-1/2 h-48 md:h-auto">
            <img :src="current.photo ? '/storage/' + current.photo : 'https://picsum.photos/300/300?random=' + currentIndex" 
                 :alt="current.name" class="w-full h-full object-cover">
        </div>

        {{-- Info --}}
        <div class="md:w-1/2 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2" x-text="current.name"></h3>
                <p class="text-gray-500 italic mb-4" x-text="'Born in ' + current.birth_year"></p>
                <p class="text-gray-700 text-justify text-sm md:text-base" x-text="current.description"></p>
            </div>

            {{-- Controls --}}
            <div class="flex justify-between mt-4">
                <button @click="prev()" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm md:text-base">← Previous</button>
                <button @click="next()" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 text-sm md:text-base">Next →</button>
            </div>
        </div>

        {{-- Close --}}
        <button @click="close()" class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 font-bold text-2xl md:text-3xl">&times;</button>
    </div>
</div>

</div>

{{-- Scripts --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({ duration: 600, once: true });

    // Hero parallax
    const heroImg = document.querySelector('#hero img');
    if(heroImg){
        window.addEventListener('scroll', () => {
            heroImg.style.transform = `translateY(${window.scrollY*0.25}px) scale(1.05)`;
        });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor=>{
        anchor.addEventListener('click', function(e){
            const target = document.querySelector(this.getAttribute('href'));
            if(target){
                e.preventDefault();
                window.scrollTo({top: target.offsetTop, behavior:'smooth'});
            }
        });
    });
});

// AlpineJS founder modal
function founderModal(){
    return {
        open: false,
        currentIndex: 0,
        founders: @json($founders),
        current: {},
        openModal(index){
            this.currentIndex = index;
            this.current = this.founders[index];
            this.open = true;
        },
        close(){ this.open = false },
        prev(){
            this.currentIndex = (this.currentIndex - 1 + this.founders.length) % this.founders.length;
            this.current = this.founders[this.currentIndex];
        },
        next(){
            this.currentIndex = (this.currentIndex + 1) % this.founders.length;
            this.current = this.founders[this.currentIndex];
        }
    }
}

</script>
