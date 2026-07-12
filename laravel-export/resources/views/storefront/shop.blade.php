@extends('layouts.app')

@section('title', 'Shop — Juwel Trade Corporation')

@section('content')
<div class="jtc" x-data="shop(@js([
    'products'   => $products,
    'categories' => $categories,
    'brands'     => $brands,
]))">

    {{-- Header (shop-scoped: search bound to the shop filter) --}}
    <header class="jtc-header">
        <div class="jtc-header__inner">
            <a href="{{ route('storefront.index') }}" class="jtc-logo">
                <span class="jtc-logo__mark"><img src="{{ asset('images/jtc-logo.jpeg') }}" alt="Juwel Trade Corporation"></span>
                <span>
                    <span class="jtc-logo__name">Juwel Trade</span>
                    <span class="jtc-logo__sub">Corporation</span>
                </span>
            </a>

            <form class="jtc-search" role="search" @submit.prevent>
                <input type="search" class="jtc-search__input" x-model="search" placeholder="Search for braces, monitors, massagers…" aria-label="Search products">
                <button class="jtc-search__go" aria-label="Search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </form>

            <div class="jtc-actions">
                <a href="#" class="jtc-round-btn jtc-round-btn--cart" aria-label="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" width="23" height="23"><circle cx="9" cy="21" r="1.7"></circle><circle cx="19" cy="21" r="1.7"></circle><path d="M2.5 3h2.2l2.1 12.1a1.8 1.8 0 0 0 1.8 1.5h9.1a1.8 1.8 0 0 0 1.8-1.4l1.6-7.2H6"></path></svg>
                    <span class="jtc-cart-badge" x-show="cart.length" x-text="cart.reduce((n,l)=>n+l.qty,0)" x-cloak></span>
                </a>
                <a href="#" class="jtc-round-btn jtc-round-btn--account" aria-label="Account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Page head --}}
    <div class="jtc-shop__head">
        <div class="jtc-shop__crumb"><a href="{{ route('storefront.index') }}" style="color:inherit;text-decoration:none">Home</a> <span>/</span> <span style="color:#14201c;font-weight:600">Shop</span></div>
        <div class="jtc-shop__headrow">
            <h1>Shop all products</h1>
            <span class="jtc-shop__count"><span x-text="total"></span> product<span x-show="total !== 1">s</span> found</span>
        </div>
    </div>

    {{-- Body --}}
    <div class="jtc-shop__body">
        <aside class="jtc-filters">
            @include('storefront.partials.shop-filters')
        </aside>

        <div class="jtc-shop__main">
            {{-- toolbar --}}
            <div class="jtc-toolbar">
                <button class="jtc-toolbar__filterbtn" @click="mobileFiltersOpen = true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="4" y1="6" x2="20" y2="6"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="10" y1="18" x2="14" y2="18"></line></svg>
                    Filters <span x-show="activeChips.length" x-text="activeChips.length" x-cloak></span>
                </button>
                <div class="jtc-toolbar__sort">
                    <span>Sort by</span>
                    <select x-model="sort">
                        <option value="popularity">Most popular</option>
                        <option value="newest">Newest</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="rating">Top rated</option>
                    </select>
                </div>
            </div>

            {{-- active chips --}}
            <div class="jtc-chips" x-show="activeChips.length" x-cloak>
                <template x-for="(chip, i) in activeChips" :key="i">
                    <button class="jtc-chip" @click="chip.clear()">
                        <span x-text="chip.label"></span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" width="12" height="12"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
                    </button>
                </template>
            </div>

            {{-- grid --}}
            <div x-show="total > 0">
                <div class="jtc-shopgrid">
                    <template x-for="p in paged" :key="p.id">
                        @include('storefront.partials.shop-card')
                    </template>
                </div>

                <div class="jtc-pagination">
                    <button :disabled="currentPage === 1" @click="page = Math.max(1, currentPage - 1)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <template x-for="n in pageNumbers" :key="n">
                        <button :class="n === currentPage && 'is-active'" @click="page = n" x-text="n"></button>
                    </template>
                    <button :disabled="currentPage === totalPages" @click="page = Math.min(totalPages, currentPage + 1)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            </div>

            {{-- empty --}}
            <div class="jtc-empty" x-show="total === 0" x-cloak>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="56" height="56"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <p>No products match your filters</p>
                <p>Try clearing some filters or search terms.</p>
                <button class="jtc-btn jtc-btn--primary" style="padding:10px 20px" @click="clearFilters()">Clear all filters</button>
            </div>
        </div>
    </div>

    {{-- mobile filter drawer --}}
    <div class="jtc-scrim" :class="mobileFiltersOpen && 'is-open'" @click="mobileFiltersOpen = false" x-cloak></div>
    <aside class="jtc-fdrawer" :class="mobileFiltersOpen && 'is-open'" x-cloak>
        <div class="jtc-fdrawer__head">
            <h3>Filters</h3>
            <button class="jtc-fdrawer__close" aria-label="Close" @click="mobileFiltersOpen = false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
        </div>
        <div class="jtc-fdrawer__body">
            @include('storefront.partials.shop-filters', ['bare' => true])
        </div>
        <div class="jtc-fdrawer__foot">
            <button class="jtc-btn jtc-btn--primary jtc-btn--block" style="padding:13px" @click="mobileFiltersOpen = false">Show <span x-text="total"></span> results</button>
        </div>
    </aside>

    {{-- toast --}}
    <div class="jtc-toast" :class="toastShow && 'is-open'" x-cloak>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span x-text="toastMsg"></span>
    </div>
</div>
@endsection
