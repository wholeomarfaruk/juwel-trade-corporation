@extends('layouts.app')

{{-- The .jtc wrapper, x-data, topbar, header, footer and overlays live in
     layouts.app so every page shares the JTC shell. This page supplies only
     the homepage sections.

     Sections below render as lazy Livewire components: the page shell (topbar,
     header, footer, nav) paints immediately, and each section streams in with
     its own DB query once it's ready — a shimmer skeleton holds its place
     until then. Only "Shop by category" stays a plain Blade include, since it
     shares live Alpine state (selectedCategory) with the header search
     dropdown and off-canvas menu, and has no query to defer. --}}

@section('content')

    @livewire('website.storefront.hero-section')

    @include('storefront.partials.category-carousel')

    {{-- Category sections: pass only category_id (+ optional style/limit/rail).
         Name/link/products auto-resolve and the component is null-safe when a
         category_id is missing or doesn't exist — it just renders nothing. --}}
    @php $homeCategoryIds = ($cat_isshowhome ?? collect())->pluck('id'); @endphp

    {{-- 1st homepage category: full-width banner (image + grid) --}}
    @if ($homeCategoryIds->get(0))
        @livewire('website.storefront.category-section', [
            'category_id' => $homeCategoryIds->get(0),
        ])
    @endif

    @livewire('website.storefront.promo-strip-section')



    {{-- Next 2 homepage categories: compact rails --}}
    @foreach ($homeCategoryIds->get(1) as $categoryId)
        @livewire('website.storefront.category-section', [
            'category_id' => $categoryId,
        ], key('cat-section-' . $categoryId))
    @endforeach

    @livewire('website.storefront.promos-section')
    @livewire('website.storefront.browse-all-section')

    {{-- Remaining homepage categories (up to 5): default full-width grid --}}
    @foreach ($homeCategoryIds->slice(2, 5) as $categoryId)
        @livewire('website.storefront.category-section', [
            'category_id' => $categoryId,
            'rail'        => false,
        ], key('cat-grid-' . $categoryId))
    @endforeach

    @livewire('website.storefront.discover-chips-section')
@endsection
