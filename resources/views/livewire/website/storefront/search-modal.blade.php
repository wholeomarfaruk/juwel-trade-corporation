<div class="jtc-modal-scrim" :class="searchModalOpen && 'is-open'" @click="searchModalOpen = false" x-cloak>
    <div class="jtc-modal jtc-modal--search" @click.stop
         x-effect="if (searchModalOpen) $refs.searchInput?.focus()">

        <div class="jtc-searchmodal__top">
            <div class="jtc-modal__head">
                <button class="jtc-modal__close" aria-label="Close search" @click="searchModalOpen = false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
                </button>
                <h3>Search products</h3>
            </div>

            <form class="jtc-search jtc-search--modal" role="search" @submit.prevent>
                <button type="button" class="jtc-search__cat" @click="searchModalCatOpen = !searchModalCatOpen">
                    <span>{{ $category ?? 'All categories' }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>

                <div class="jtc-search__dropdown" :class="searchModalCatOpen && 'is-open'" x-cloak>
                    <button type="button" wire:click="selectCategory(null)" @click="searchModalCatOpen = false"
                            @class(['jtc-search__option' => true, 'is-active' => ! $category])>All categories</button>
                    @foreach ($categories as $cat)
                        <button type="button" wire:click="selectCategory(@js($cat['name']))" @click="searchModalCatOpen = false"
                                @class(['jtc-search__option' => true, 'is-active' => $category === $cat['name']])>{{ $cat['name'] }}</button>
                    @endforeach
                </div>
                <div class="jtc-search__scrim" x-show="searchModalCatOpen" @click="searchModalCatOpen = false" x-cloak></div>

                <input type="search" class="jtc-search__input" placeholder="Search for braces, monitors, massagers…"
                       aria-label="Search products" x-ref="searchInput"
                       wire:model.live.debounce.400ms="query">
                <span class="jtc-search__go">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
            </form>
        </div>

        <div class="jtc-search-results">
            <div wire:loading.delay class="jtc-search-results__loading">Searching…</div>

            <div wire:loading.remove.delay>
                @if (trim($query) === '')
                    <p class="jtc-search-results__hint">Start typing to search our catalogue.</p>
                @elseif ($results->isEmpty())
                    <p class="jtc-search-results__hint">No products found for "{{ $query }}".</p>
                @else
                    <ul class="jtc-search-results__list">
                        @foreach ($results as $p)
                            <li class="jtc-searchresult">
                                <a href="#" class="jtc-searchresult__media">
                                    <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" loading="lazy">
                                </a>
                                <div class="jtc-searchresult__body">
                                    <a href="#" class="jtc-searchresult__name">{{ $p['name'] }}</a>
                                    <div class="jtc-searchresult__prices">
                                        @if ($p['priceIsCompare'])
                                            <span class="jtc-searchresult__price jtc-searchresult__price--sale">{{ $p['priceText'] }}</span>
                                            <span class="jtc-searchresult__was">{{ $p['compareText'] }}</span>
                                        @else
                                            <span class="jtc-searchresult__price">{{ $p['priceText'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <button class="jtc-btn jtc-btn--primary jtc-searchresult__add"
                                        @click="$dispatch('add-to-cart', { productId: {{ $p['id'] }} }); cartOpen = true; searchModalOpen = false">
                                    Add to cart
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('search', ['search' => $query]) }}" class="jtc-search-results__seeall">
                        See all results for "{{ $query }}"
                        @include('storefront.partials.icons.arrow')
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
