@extends('layouts.app')

{{-- The .jtc wrapper, x-data, topbar, header, footer and overlays live in
     layouts.app so every page shares the JTC shell. This page supplies only
     the homepage sections. --}}

@section('content')
    @include('storefront.partials.hero')
    @include('storefront.partials.category-carousel')

    {{-- Today's best deals --}}
    <section class="jtc-section">
        <div class="jtc-shell">
            <div class="jtc-section-head">
                <h2 class="jtc-h2">Today's best deals</h2>
                <a href="#" class="jtc-seeall">See all deals @include('storefront.partials.icons.arrow')</a>
            </div>
            <div class="jtc-rail">
                @foreach ($deals as $p)
                    @include('storefront.partials.product-card', ['p' => $p, 'rail' => true])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Full-width promo strip --}}
    <section class="jtc-section jtc-section--strip">
        <div class="jtc-shell">
            <a href="#" class="jtc-promo" style="height:100px;min-height:0">
                <img src="{{ asset('images/promos/deal-strip.jpg') }}" alt="Promotional banner">
            </a>
        </div>
    </section>

    {{-- Best sellers --}}
    <section class="jtc-section">
        <div class="jtc-shell">
            <div class="jtc-section-head">
                <h2 class="jtc-h2">Best selling items</h2>
                <a href="#" class="jtc-seeall">See all @include('storefront.partials.icons.arrow')</a>
            </div>
            <div class="jtc-rail">
                @foreach ($bestSellers as $p)
                    @include('storefront.partials.product-card', ['p' => $p, 'rail' => true])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Promos --}}
    <section class="jtc-section jtc-section--tight">
        <div class="jtc-shell">
            <div class="jtc-promos">
                @foreach ($promos as $i => $promo)
                    <a href="#" class="jtc-promo">
                        <img src="{{ $promo['image'] }}" alt="Promotion" loading="lazy">
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Browse our products --}}
    <section class="jtc-section">
        <div class="jtc-shell">
            <div class="jtc-section-head">
                <h2 class="jtc-h2">Browse our products</h2>
                <a href="#" class="jtc-seeall">See all @include('storefront.partials.icons.arrow')</a>
            </div>
            <div class="jtc-grid">
                @foreach ($browseAll as $p)
                    @include('storefront.partials.product-card', ['p' => $p, 'rail' => false])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Discover more --}}
    <section class="jtc-section jtc-section--tight">
        <div class="jtc-shell">
            <div style="margin-bottom:26px"><h2 class="jtc-h2">Discover more</h2></div>
            <div class="jtc-chips">
                @foreach ($discoverChips as $chip)
                    <a href="#" class="jtc-chip">{{ $chip }}</a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
