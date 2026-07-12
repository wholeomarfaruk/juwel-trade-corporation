<section class="jtc-section">
    <div class="jtc-shell">
        <div class="jtc-section-head">
            <h2 class="jtc-h2">Browse our products</h2>
            <a href="{{ route('shop') }}" class="jtc-seeall">See all @include('storefront.partials.icons.arrow')</a>
        </div>
        <div class="jtc-grid">
            @foreach ($browseAll as $p)
                @include('storefront.partials.product-card', ['p' => $p, 'rail' => false])
            @endforeach
        </div>
    </div>
</section>
