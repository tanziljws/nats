<section id="hero" class="relative relative h-[100vh] w-screen overflow-hidden">
    {{-- Hero Image --}}
    <img 
        src="{{ $profile && $profile->hero_image ? asset('storage/' . $profile->hero_image) : 'https://picsum.photos/1600/900?blur' }}"
        alt="School Hero" 
        class="absolute inset-0 w-full h-full object-cover brightness-[0.6]"
    >


    {{-- Overlay Konten --}}
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">

        {{-- Logo (opsional) --}}
        @if($profile && !empty($profile->logo))
            <div class="mt-8 flex justify-center" data-aos="zoom-in" data-aos-delay="100">
                <img src="{{ asset('storage/' . $profile->logo) }}" 
                     alt="School Logo" 
                     class="h-28 w-auto rounded-xl shadow-lg   p-3">
            </div>
        @endif

        {{-- Judul --}}
        <h1 class="text-5xl md:text-6xl font-serif font-extrabold mb-4 leading-tight
                text-white tracking-wide
                drop-shadow-[0_1px_4px_rgba(0,0,0,0.6)]
                transition-all duration-700"
            style="text-shadow: 0 0 12px rgba(255, 215, 100, 0.25), 0 0 20px rgba(255, 215, 100, 0.15);"
            data-aos="fade-up" data-aos-delay="300">
            Welcome to<br> {{ $profile && $profile->title ? $profile->title : 'Hogwarts School of Witchcraft and Wizardry' }}
        </h1>


        {{-- Subjudul --}}
        <p class="text-lg md:text-xl mb-6 italic text-gray-200 font-light drop-shadow-sm"
           data-aos="zoom-in" data-aos-delay="500">
           A legacy of knowledge, courage, and unity since the dawn of wizardry.
        </p>

        
        {{-- CTA Button --}}
        <a href="#about"
        style="
            background: linear-gradient(90deg, #7a1b1b 0%, #3b4f5f 50%, #243c7a 100%);
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
        "
        class="relative overflow-hidden group inline-block px-7 py-3 md:px-9 md:py-4 font-serif rounded-2xl
                text-white tracking-wide shadow-[0_4px_10px_rgba(0,0,0,0.25)]
                hover:scale-105 hover:shadow-[0_6px_16px_rgba(0,0,0,0.35)]"
        data-aos="fade-up" data-aos-delay="600">
            <span class="relative z-10">Explore More</span>
        </a>


        
    </div>
    
</section>
