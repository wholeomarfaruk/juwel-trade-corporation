<section class="jtc-section">
    <div class="jtc-shell">
        <div class="jtc-section-head">
            <h2 class="jtc-h2">Best selling items</h2>
            <a href="#" class="jtc-seeall">See all @include('storefront.partials.icons.arrow')</a>
        </div>
        <div class="{{ $style === 'grid' ? 'jtc-grid' : 'jtc-rail' }}">
            @foreach ($bestSellers as $p)
                @include('storefront.partials.product-card', ['p' => $p, 'rail' => $style !== 'grid'])
            @endforeach
        </div>
    </div>
</section>
