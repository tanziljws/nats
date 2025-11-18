@php
    use App\Models\SchoolProfile;
    $schoolProfile = SchoolProfile::first();
@endphp

<footer class="bg-[#0d0d0d] text-gray-200 font-serif">
    <div class="container mx-auto px-6 py-12 grid md:grid-cols-2 lg:grid-cols-3 gap-10">
        {{-- School Info --}}
        <div>
            <h3 class="text-2xl font-bold mb-3 flex items-center gap-2 leading-tight break-words">
                <span class="w-1.5 h-6 rounded-full" style="
                    background: linear-gradient(180deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);
                    display: inline-block;
                    vertical-align: middle;
                    font-weight: 400;
                "></span>
                {{ $schoolProfile->title ?? 'Hogwarts School' }}
            </h3>
            <p class="text-sm leading-relaxed text-gray-300">
                {{ $schoolProfile->address ?? 'Scotland, United Kingdom' }}
            </p>
            <p class="mt-3 italic text-gray-400">
                “{{ $schoolProfile->motto ?? 'Draco Dormiens Nunquam Titillandus' }}”
            </p>
        </div>

        {{-- Contact & Social (Icon-only) --}}
        <div>
            <h3 class="text-2xl font-bold mb-3 flex items-center gap-2">
                <span class="w-1.5 h-6 rounded-full font-serif" style="
                    background: linear-gradient(180deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);
                    display: inline-block;
                    vertical-align: middle;
                    font-weight: 400;
                "></span>
                Contact
            </h3>
            <div class="flex flex-col gap-3">
                @if(!empty($schoolProfile->email))
                    <div class="flex items-center gap-3">
                        <a href="mailto:{{ $schoolProfile->email }}" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition shrink-0" aria-label="Email">
                            <i class="fas fa-envelope text-white"></i>
                        </a>
                        <a href="mailto:{{ $schoolProfile->email }}" class="text-sm text-gray-200 hover:text-white break-all">{{ $schoolProfile->email }}</a>
                    </div>
                @endif
                @if(!empty($schoolProfile->phone))
                    <div class="flex items-center gap-3">
                        <a href="tel:{{ preg_replace('/\s+/', '', $schoolProfile->phone) }}" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition shrink-0" aria-label="Phone">
                            <i class="fas fa-phone text-white"></i>
                        </a>
                        <a href="tel:{{ preg_replace('/\s+/', '', $schoolProfile->phone) }}" class="text-sm text-gray-200 hover:text-white break-all">{{ $schoolProfile->phone }}</a>
                    </div>
                @endif
            </div>

            <div class="mt-5">
                <div class="flex items-center gap-3">
                    @if(!empty($schoolProfile->facebook_url))
                        <a href="{{ $schoolProfile->facebook_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" aria-label="Facebook">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                    @endif
                    @if(!empty($schoolProfile->instagram_url))
                        <a href="{{ $schoolProfile->instagram_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" aria-label="Instagram">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                    @endif
                    @if(!empty($schoolProfile->youtube_url))
                        <a href="{{ $schoolProfile->youtube_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" aria-label="YouTube">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    @endif
                    @if(!empty($schoolProfile->twitter_url))
                        <a href="{{ $schoolProfile->twitter_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition" aria-label="Twitter">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Google Maps (Dynamic Embed) --}}
        <div>
            <h3 class="text-2xl font-bold mb-3 flex items-center gap-2">
                <span class="w-1.5 h-6 rounded-full" style="
                    background: linear-gradient(180deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);
                    display: inline-block;
                    vertical-align: middle;
                    font-weight: 400;
                "></span>
                Location
            </h3>

            @if (!empty($schoolProfile->map_embed))
                <div class="rounded-xl overflow-hidden shadow-md border border-[#3c5e5e]">
                    <div class="relative w-full" style="padding-top: 56.25%">
                        <div class="absolute inset-0 [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0">
                            {!! $schoolProfile->map_embed !!}
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-[#141414] border border-gray-700 rounded-xl text-sm text-gray-400">
                    <p>No map embedded yet. Please upload one in School Profile settings.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-[#3c5e5e] text-center py-4 text-sm text-gray-400 tracking-wide bg-[#101010] font-serif">
        © {{ date('Y') }} 
        <span style="
            background: linear-gradient(90deg, #b03535 0%, #3c5e5e 50%, #425d9e 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 300;
        ">{{ $schoolProfile->title ?? 'Hogwarts School' }}</span>. All rights reserved.
    </div>
</footer>

