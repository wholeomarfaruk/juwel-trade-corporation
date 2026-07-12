{{-- Alpine-templated product card (inside <template x-for="p in paged">). --}}
<article class="jtc-card">
    <div class="jtc-card__media">
        <div class="jtc-card__badges">
            <span class="jtc-badge jtc-badge--new" x-show="p.showNew">New</span>
            <span class="jtc-badge jtc-badge--deal" x-show="p.showDealPct" x-text="p.pctText"></span>
        </div>
        <button class="jtc-wish" :class="isWished(p.id) && 'is-wished'" aria-label="Save" @click="toggleWish(p.id)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><path d="M12 21s-7-4.5-9.5-9A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9.5 6c-2.5 4.5-9.5 9-9.5 9Z"></path></svg>
        </button>
        <img :src="p.image" :alt="p.name" loading="lazy">
    </div>
    <div class="jtc-card__body">
        <a href="#" class="jtc-card__title" x-text="p.name"></a>
        <div class="jtc-card__prices">
            <template x-if="p.priceIsCompare">
                <span class="jtc-card__price jtc-card__price--sale" x-text="p.priceText"></span>
            </template>
            <template x-if="p.priceIsCompare">
                <span class="jtc-card__was" x-text="p.compareText"></span>
            </template>
            <template x-if="p.priceIsRange">
                <span class="jtc-card__price jtc-card__price--range" x-text="p.rangeText"></span>
            </template>
            <template x-if="p.priceSingle">
                <span class="jtc-card__price" x-text="p.priceText"></span>
            </template>
        </div>
        <button class="jtc-btn jtc-btn--primary jtc-card__add" @click="addToCart(p.id)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M6 6h15l-1.5 9h-12z"></path><circle cx="9" cy="20" r="1.4"></circle><circle cx="18" cy="20" r="1.4"></circle></svg>
            Add to cart
        </button>
    </div>
</article>
