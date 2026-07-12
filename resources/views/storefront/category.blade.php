@extends('layouts.app')
@section('segment', $segment ?? '')
@section('page_title', ($category->name ?? 'Category') . ' | ' . ($site['site_name'] ?? 'Gramer Dokan'))
@push('meta')
    @php
        $_sn       = $site['site_name'] ?? 'Gramer Dokan';
        $_fb       = !empty($site['favicon']) ? asset('storage/'.$site['favicon']) : asset('frontend/img/seldom-rounded.png');
        $metaDesc  = $category->description ?? 'Shop ' . ($category->name ?? '') . ' collection at ' . $_sn . '. Premium quality clothing at the best price.';
        $metaDesc  = \Illuminate\Support\Str::limit(strip_tags((string) $metaDesc), 155);
        $metaImage = $category->getImageUrl() ?? $_fb;
        $metaUrl   = url()->current();
    @endphp
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $metaUrl }}">

    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ ($category->name ?? 'Category') . ' | ' . $_sn }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:image"       content="{{ $metaImage }}">
    <meta property="og:url"         content="{{ $metaUrl }}">
    <meta property="og:site_name"   content="{{ $_sn }}">

    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ ($category->name ?? 'Category') . ' | ' . $_sn }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image"       content="{{ $metaImage }}">
@endpush

{{-- Same design as storefront.shop — the .jtc wrapper, x-data, header and
     footer live in layouts.app. Search/brand/price filters are UI-only for
     now (not yet wired to a controller); the category checkbox for the
     current category is pre-checked and disabled since we're already
     scoped to it. Product grid uses real DB data via the shared
     product-card partial, and pagination is Laravel's own paginator. --}}

@section('content')

    {{-- Page head --}}
    <div class="jtc-shop__head">
        <div class="jtc-shop__crumb">
            <a href="{{ route('home') }}" style="color:inherit;text-decoration:none">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}" style="color:inherit;text-decoration:none">Shop</a>
            <span>/</span>
            <span style="color:#14201c;font-weight:600">{{ $category->name }}</span>
        </div>
        <div class="jtc-shop__headrow">
            <h1>{{ $category->name }}</h1>
            <span class="jtc-shop__count">{{ $products->total() }} product{{ $products->total() === 1 ? '' : 's' }} found</span>
        </div>
    </div>

    {{-- Body --}}
    <div class="jtc-shop__body">
        <aside class="jtc-filters">
            @include('storefront.partials.shop-filters', ['activeCategoryId' => $category->id])
        </aside>

        <div class="jtc-shop__main">
            {{-- toolbar --}}
            <div class="jtc-toolbar">
                <button type="button" class="jtc-toolbar__filterbtn" @click="mobileFiltersOpen = true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="4" y1="6" x2="20" y2="6"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="10" y1="18" x2="14" y2="18"></line></svg>
                    Filters
                </button>
                <div class="jtc-toolbar__sort">
                    <span>Sort by</span>
                    <select>
                        <option value="popularity">Most popular</option>
                        <option value="newest">Newest</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="rating">Top rated</option>
                    </select>
                </div>
            </div>

            {{-- grid --}}
            @if ($products->isNotEmpty())
                <div class="jtc-shopgrid">
                    @foreach ($products as $product)
                        @include('storefront.partials.product-card', [
                            'p'    => \App\Support\StorefrontData::decorateEloquentProduct($product),
                            'rail' => false,
                        ])
                    @endforeach
                </div>

                <div class="jtc-pagination">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                {{-- empty --}}
                <div class="jtc-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="56" height="56"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <p>No products found</p>
                    <p>Please check back soon.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- mobile filter drawer --}}
    <div class="jtc-scrim" :class="mobileFiltersOpen && 'is-open'" @click="mobileFiltersOpen = false" x-cloak></div>
    <aside class="jtc-fdrawer" :class="mobileFiltersOpen && 'is-open'" x-cloak>
        <div class="jtc-fdrawer__head">
            <h3>Filters</h3>
            <button type="button" class="jtc-fdrawer__close" aria-label="Close" @click="mobileFiltersOpen = false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
        </div>
        <div class="jtc-fdrawer__body">
            @include('storefront.partials.shop-filters', ['bare' => true, 'activeCategoryId' => $category->id])
        </div>
        <div class="jtc-fdrawer__foot">
            <button type="button" class="jtc-btn jtc-btn--primary jtc-btn--block" style="padding:13px" @click="mobileFiltersOpen = false">Show results</button>
        </div>
    </aside>
@endsection
