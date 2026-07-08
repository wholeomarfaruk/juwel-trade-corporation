<section class="jtc-section jtc-section--hero">
    <div class="jtc-shell">
        <div class="jtc-hero">

            {{-- left: auto-rotating slider --}}
            <div class="jtc-hero__slider">
                @foreach ($slides as $i => $slide)
                    <div class="jtc-hero__slide" :class="heroIndex === {{ $i }} && 'is-active'" @if($i === 0) style="opacity:1;visibility:visible" @endif>
                        <img src="{{ $slide['image'] }}" alt="">
                    </div>
                @endforeach

                <div class="jtc-hero__dots">
                    @foreach ($slides as $i => $slide)
                        <button class="jtc-hero__dot" :class="heroIndex === {{ $i }} && 'is-active'" aria-label="Go to slide {{ $i + 1 }}" @click="heroGo({{ $i }}); _restartHero()"></button>
                    @endforeach
                </div>

                <div class="jtc-hero__nav">
                    <button class="jtc-hero__arrow" aria-label="Previous" @click="heroPrev()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button class="jtc-hero__arrow" aria-label="Next" @click="heroNext()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            </div>

            {{-- right: two static banners --}}
            <div class="jtc-hero__side">
                @foreach ($heroBanners as $banner)
                    <a href="#" class="jtc-hero__banner">
                        <img src="{{ $banner['image'] }}" alt="" loading="lazy">
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
