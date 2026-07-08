<div class="jtc-offcanvas" :class="mmenuOpen && 'is-open'" x-cloak>
    <div class="jtc-offcanvas__head">
        <span>Menu</span>
        <button class="jtc-offcanvas__close" aria-label="Close menu" @click="closeAll()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
        </button>
    </div>

    <div class="jtc-offcanvas__label">Shop</div>
    <nav class="jtc-offcanvas__nav">
        <a href="#" class="jtc-offcanvas__link jtc-offcanvas__link--sale">Sale</a>
        <a href="#" class="jtc-offcanvas__link">Shop all</a>
        <a href="#" class="jtc-offcanvas__link">Brands</a>
        <a href="#" class="jtc-offcanvas__link">New arrivals</a>
        <a href="#" class="jtc-offcanvas__link">Best sellers</a>
    </nav>

    <div class="jtc-offcanvas__label">Categories</div>
    <nav class="jtc-offcanvas__nav" style="padding-bottom:10px">
        @foreach ($categories as $cat)
            <a href="#" class="jtc-offcanvas__link" @click.prevent="selectCategory(@js($cat['name'])); closeAll()">{{ $cat['name'] }}</a>
        @endforeach
    </nav>
</div>
