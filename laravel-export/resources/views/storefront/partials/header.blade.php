<header class="jtc-header">
    <div class="jtc-header__inner">

        <button class="jtc-icon-btn jtc-hamburger" aria-label="Open menu" @click="openMenu()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>

        <a href="#" class="jtc-logo">
            <span class="jtc-logo__mark">
                <img src="{{ asset('images/jtc-logo.jpeg') }}" alt="Juwel Trade Corporation">
            </span>
            <span>
                <span class="jtc-logo__name">Juwel Trade</span>
                <span class="jtc-logo__sub">Corporation</span>
            </span>
        </a>

        <form class="jtc-search" role="search" @submit.prevent>
            <button type="button" class="jtc-search__cat" @click="searchCatOpen = !searchCatOpen">
                <span x-text="selectedCategory || 'All categories'">All categories</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>

            <div class="jtc-search__dropdown" :class="searchCatOpen && 'is-open'" x-cloak>
                <button type="button" class="jtc-search__option" :class="!selectedCategory && 'is-active'"
                        @click="selectedCategory = null; searchCatOpen = false">All categories</button>
                @foreach ($categories as $cat)
                    <button type="button" class="jtc-search__option"
                            :class="selectedCategory === @js($cat['name']) && 'is-active'"
                            @click="selectCategory(@js($cat['name']))">{{ $cat['name'] }}</button>
                @endforeach
            </div>
            <div class="jtc-search__scrim" x-show="searchCatOpen" @click="searchCatOpen = false" x-cloak></div>

            <input type="search" class="jtc-search__input" placeholder="Search for braces, monitors, massagers…" aria-label="Search products">
            <button class="jtc-search__go" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
        </form>

        <div class="jtc-actions">
            <button class="jtc-round-btn jtc-round-btn--support" aria-label="Call support" title="Call support" @click="openSupport()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1 .37 1.94.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.87.35 1.81.59 2.81.72A2 2 0 0 1 22 16.92z"></path></svg>
            </button>

            <button class="jtc-round-btn jtc-round-btn--cart" aria-label="Open cart" @click="openCart()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" width="23" height="23"><circle cx="9" cy="21" r="1.7"></circle><circle cx="19" cy="21" r="1.7"></circle><path d="M2.5 3h2.2l2.1 12.1a1.8 1.8 0 0 0 1.8 1.5h9.1a1.8 1.8 0 0 0 1.8-1.4l1.6-7.2H6"></path></svg>
                <span class="jtc-cart-badge" x-show="cartCount > 0" x-text="cartCount" x-cloak></span>
            </button>

            <button class="jtc-round-btn jtc-round-btn--account" aria-label="Login / Sign up" title="Login / Sign up" @click="openAuthModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
            </button>
        </div>
    </div>
</header>
