<div class="jtc-offcanvas" :class="mmenuOpen && 'is-open'" x-cloak>
    <div class="jtc-offcanvas__head">
        <span>Menu</span>
        <button class="jtc-offcanvas__close" aria-label="Close menu" @click="closeAll()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
        </button>
    </div>

    <div class="jtc-offcanvas__label">Shop</div>
    <nav class="jtc-offcanvas__nav">
        <a href="{{ route('shop') }}" class="jtc-offcanvas__link">Shop all</a>
        {{-- <a href="#" class="jtc-offcanvas__link">Brands</a>
        <a href="#" class="jtc-offcanvas__link">New arrivals</a>
        <a href="#" class="jtc-offcanvas__link">Best sellers</a> --}}
    </nav>

    <div class="jtc-offcanvas__label">Categories</div>
    <nav class="jtc-offcanvas__nav" style="padding-bottom:10px">
        @foreach ($categories as $cat)
            @if (count($cat['children']))
                <div x-data="{ open: false }" class="jtc-offcanvas__group">
                    <div class="jtc-offcanvas__row">
                        <a href="{{ route('category.show', $cat['slug']) }}" class="jtc-offcanvas__link" @click="closeAll()">
                            <img src="{{ $cat['image'] }}" alt="" class="jtc-offcanvas__link-img" loading="lazy">
                            <span>{{ $cat['name'] }}</span>
                        </a>
                        <button type="button" class="jtc-offcanvas__toggle" :class="open && 'is-open'"
                            @click="open = !open" :aria-expanded="open" aria-label="Toggle {{ $cat['name'] }} subcategories">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="jtc-offcanvas__submenu" x-show="open"
                        x-transition:enter="jtc-offcanvas__submenu-enter"
                        x-transition:leave="jtc-offcanvas__submenu-leave">
                        @foreach ($cat['children'] as $child)
                            <a href="{{ route('subcategory.show', [$cat['slug'], $child['slug']]) }}"
                                class="jtc-offcanvas__link jtc-offcanvas__link--sub" @click="closeAll()">{{ $child['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ route('category.show', $cat['slug']) }}" class="jtc-offcanvas__link" @click="closeAll()">
                    <img src="{{ $cat['image'] }}" alt="" class="jtc-offcanvas__link-img" loading="lazy">
                    <span>{{ $cat['name'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
</div>
