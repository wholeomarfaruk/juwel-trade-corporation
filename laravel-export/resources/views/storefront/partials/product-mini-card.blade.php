{{-- Server-rendered product card for the product page (related / recommended).
     Expects: $p (decorated product array), $rail (bool). Uses the product() Alpine
     component's addProduct()/toggleWish() so cart + wishlist stay in sync. --}}
<article class="jtc-card {{ $rail ? 'jtc-card--rail' : '' }}">
    <div class="jtc-card__media">
        <div class="jtc-card__badges">
            @if ($p['showNew'])<span class="jtc-badge jtc-badge--new">New</span>@endif
            @if ($p['showDealPct'])<span class="jtc-badge jtc-badge--deal">{{ $p['pctText'] }}</span>@endif
        </div>
        <button class="jtc-wish" :class="isWished({{ $p['id'] }}) && 'is-wished'" aria-label="Save" @click="toggleWish({{ $p['id'] }})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><path d="M12 21s-7-4.5-9.5-9A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9.5 6c-2.5 4.5-9.5 9-9.5 9Z"></path></svg>
        </button>
        <a href="{{ route('storefront.product', $p['sku']) }}"><img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" loading="lazy"></a>
    </div>
    <div class="jtc-card__body">
        <a href="{{ route('storefront.product', $p['sku']) }}" class="jtc-card__title">{{ $p['name'] }}</a>
        <div class="jtc-card__prices">
            @if ($p['priceIsCompare'])
                <span class="jtc-card__price jtc-card__price--sale">{{ $p['priceText'] }}</span>
                <span class="jtc-card__was">{{ $p['compareText'] }}</span>
            @else
                <span class="jtc-card__price">{{ $p['priceText'] }}</span>
            @endif
        </div>
        <button class="jtc-btn jtc-btn--primary jtc-card__add" @click="addProduct(@js($p))">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M6 6h15l-1.5 9h-12z"></path><circle cx="9" cy="20" r="1.4"></circle><circle cx="18" cy="20" r="1.4"></circle></svg>
            Add to cart
        </button>
    </div>
</article>
