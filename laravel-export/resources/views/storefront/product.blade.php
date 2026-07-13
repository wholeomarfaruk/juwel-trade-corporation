@php
    $stars = fn ($r) => str_repeat('★', (int) round($r)) . str_repeat('☆', 5 - (int) round($r));
@endphp
@extends('layouts.app')

@section('title', $product['name'] . ' — Juwel Trade Corporation')

@section('content')
<div class="jtc" x-data="product(@js([
    'product'  => $product,
    'gallery'  => $gallery,
    'videoSrc' => $videoSrc,
]))">

    {{-- Header --}}
    <header class="jtc-header">
        <div class="jtc-header__inner">
            <a href="{{ route('storefront.index') }}" class="jtc-logo">
                <span class="jtc-logo__mark"><img src="{{ asset('images/jtc-logo.jpeg') }}" alt="Juwel Trade Corporation"></span>
                <span>
                    <span class="jtc-logo__name">Juwel Trade</span>
                    <span class="jtc-logo__sub">Corporation</span>
                </span>
            </a>
            <form class="jtc-search" role="search" @submit.prevent action="{{ route('storefront.shop') }}">
                <input type="search" name="q" class="jtc-search__input" placeholder="Search for braces, monitors, massagers…" aria-label="Search products">
                <button class="jtc-search__go" aria-label="Search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </form>
            <div class="jtc-actions">
                <a href="#" class="jtc-round-btn jtc-round-btn--cart" aria-label="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" width="23" height="23"><circle cx="9" cy="21" r="1.7"></circle><circle cx="19" cy="21" r="1.7"></circle><path d="M2.5 3h2.2l2.1 12.1a1.8 1.8 0 0 0 1.8 1.5h9.1a1.8 1.8 0 0 0 1.8-1.4l1.6-7.2H6"></path></svg>
                    <span class="jtc-cart-badge" x-show="cartCount > 0" x-text="cartCount" x-cloak></span>
                </a>
                <a href="#" class="jtc-round-btn jtc-round-btn--account" aria-label="Account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                </a>
            </div>
        </div>
    </header>

    <div class="jtc-pd__shell">
        <div class="jtc-pd__crumb">
            <a href="{{ route('storefront.index') }}">Home</a><span class="sep">/</span>
            <a href="{{ route('storefront.shop') }}">Shop</a><span class="sep">/</span>
            {{ $categoryName }}<span class="sep">/</span>
            <span style="color:#14201c;font-weight:600">{{ $product['name'] }}</span>
        </div>

        {{-- MAIN --}}
        <div class="jtc-pd__main">

            {{-- gallery --}}
            <div class="jtc-gallery">
                <div class="jtc-gallery__thumbs">
                    @foreach ($gallery as $i => $src)
                        <button class="jtc-gallery__thumb" :class="isActiveMedia({{ $i }}) && 'is-active'" @click="activeMedia = {{ $i }}" aria-label="View image">
                            <img src="{{ $src }}" alt="">
                        </button>
                    @endforeach
                    <button class="jtc-gallery__thumb" :class="isActiveMedia(videoIndex) && 'is-active'" @click="activeMedia = videoIndex" aria-label="View video">
                        <img src="{{ $product['image'] }}" alt="">
                        <span class="jtc-gallery__play"><svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M8 5v14l11-7z"></path></svg></span>
                    </button>
                </div>

                <div class="jtc-gallery__viewer">
                    <template x-if="showingVideo">
                        <video :src="videoSrc" :poster="@js($product['image'])" controls playsinline></video>
                    </template>
                    <template x-if="!showingVideo">
                        <img :src="activeImage" alt="{{ $product['name'] }}">
                    </template>
                    <div class="jtc-gallery__badges" x-show="!showingVideo">
                        @if ($product['showNew'])<span class="jtc-badge jtc-badge--new">New</span>@endif
                        @if ($product['showDealPct'])<span class="jtc-badge jtc-badge--deal">{{ $product['pctText'] }}</span>@endif
                    </div>
                </div>
            </div>

            {{-- info --}}
            <div class="jtc-pd-info">
                <div class="jtc-pd-info__brandrow">
                    <span class="jtc-pd-info__brand">{{ $product['brand'] }}</span>
                    <span class="jtc-pd-info__dot"></span>
                    <span class="jtc-pd-info__sku">SKU: {{ $product['sku'] }}</span>
                </div>

                <h1 class="jtc-pd-info__title">{{ $product['name'] }}</h1>

                <div class="jtc-pd-info__meta">
                    <div class="jtc-pd-info__rating">
                        <span class="stars">{{ $stars($product['rating']) }}</span>
                        <strong>{{ number_format($product['rating'], 1) }}</strong>
                        <span class="muted">({{ $product['reviews'] }} reviews)</span>
                    </div>
                    <span class="jtc-pd-info__stock">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" width="14" height="14"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        In stock
                    </span>
                </div>

                <div class="jtc-pd-info__price">
                    @if ($product['priceIsCompare'])
                        <span class="jtc-pd-info__price-now">{{ $product['priceText'] }}</span>
                        <span class="jtc-pd-info__price-was">{{ $product['compareText'] }}</span>
                        <span class="jtc-pd-info__save">Save {{ $product['pctText'] }}</span>
                    @else
                        <span class="jtc-pd-info__price-now">{{ $product['priceText'] }}</span>
                    @endif
                </div>

                <p class="jtc-pd-info__short">{{ $content['short'] }}</p>

                <div class="jtc-pd-info__highlights">
                    @foreach ($content['highlights'] as $h)
                        <div class="jtc-pd-info__hl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#1B7FC4" stroke-width="2.4" width="16" height="16"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>{{ $h }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="jtc-pd-info__buyrow">
                    <div class="jtc-qty">
                        <button @click="dec()" aria-label="Decrease">−</button>
                        <span x-text="qty"></span>
                        <button @click="inc()" aria-label="Increase">+</button>
                    </div>
                    <button class="jtc-pd-actions__cart" @click="addToCart()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M6 6h15l-1.5 9h-12z"></path><circle cx="9" cy="20" r="1.4"></circle><circle cx="18" cy="20" r="1.4"></circle></svg>
                        Add to cart
                    </button>
                </div>

                <button class="jtc-pd-actions__buy" @click="buyNow()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M13 2 3 14h7v8l10-12h-7z"></path></svg>
                    Buy now
                </button>

                <div class="jtc-pd-actions__order">
                    <a href="https://wa.me/8801329732724?text={{ urlencode('Hi, I want to order: ' . $product['name']) }}" target="_blank" rel="noopener" class="jtc-pd-actions__wa">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="19" height="19"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.52 11.99c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"></path></svg>
                        Order on WhatsApp
                    </a>
                    <a href="tel:+8801329732724" class="jtc-pd-actions__call">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1 .37 1.94.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.87.35 1.81.59 2.81.72A2 2 0 0 1 22 16.92z"></path></svg>
                        Call for order
                    </a>
                </div>

                <div class="jtc-pd-info__reassure">
                    <div class="jtc-pd-info__reassure-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1B7FC4" stroke-width="2" width="18" height="18"><path d="M3 7h13v8H3z"></path><path d="M16 10h3l2 2v3h-5z"></path><circle cx="7" cy="18" r="1.6"></circle><circle cx="17" cy="18" r="1.6"></circle></svg>
                        Delivery in 24–72h
                    </div>
                    <div class="jtc-pd-info__reassure-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1B7FC4" stroke-width="2" width="18" height="18"><path d="M3 9 12 4l9 5v8l-9 5-9-5z"></path><path d="M3 9l9 5 9-5"></path></svg>
                        Easy returns
                    </div>
                    <div class="jtc-pd-info__reassure-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#1B7FC4" stroke-width="2" width="18" height="18"><rect x="3" y="5" width="18" height="14" rx="2"></rect><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        Secure payment · COD
                    </div>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="jtc-tabs">
            <div class="jtc-tabs__nav">
                <button class="jtc-tabs__btn" :class="tab === 'description' && 'is-active'" @click="setTab('description')">Description</button>
                <button class="jtc-tabs__btn" :class="tab === 'specs' && 'is-active'" @click="setTab('specs')">Specifications</button>
                <button class="jtc-tabs__btn" :class="tab === 'reviews' && 'is-active'" @click="setTab('reviews')">Reviews ({{ $product['reviews'] }})</button>
            </div>

            <div class="jtc-tabs__panel" x-show="tab === 'description'">
                @foreach ($content['paragraphs'] as $para)
                    <p>{{ $para }}</p>
                @endforeach
                <h3>Key features</h3>
                <div class="jtc-tabs__features">
                    @foreach ($content['features'] as $f)
                        <div class="jtc-tabs__feature">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#3DA935" stroke-width="2.4" width="16" height="16"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>{{ $f }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="jtc-tabs__panel" x-show="tab === 'specs'" x-cloak>
                <div class="jtc-spec">
                    @foreach ($content['specs'] as $row)
                        <div class="jtc-spec__row">
                            <span class="jtc-spec__k">{{ $row['k'] }}</span>
                            <span class="jtc-spec__v">{{ $row['v'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="jtc-tabs__panel" x-show="tab === 'reviews'" x-cloak>
                <div class="jtc-reviews-list">
                    @foreach ($content['reviews'] as $rv)
                        <div class="jtc-review">
                            <div class="jtc-review__head">
                                <span class="jtc-review__name">{{ $rv['name'] }}</span>
                                <span class="jtc-review__stars">{{ $stars($rv['rating']) }}</span>
                            </div>
                            <p>{{ $rv['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RELATED --}}
        <div class="jtc-relblock">
            <div class="jtc-relblock__head">
                <h2 class="jtc-relblock__title">Related products</h2>
                <a href="{{ route('storefront.shop') }}" class="jtc-relblock__more">View more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
            <div class="jtc-shopgrid">
                @foreach ($related as $p)
                    @include('storefront.partials.product-mini-card', ['p' => $p, 'rail' => false])
                @endforeach
            </div>
        </div>

        {{-- RECOMMENDED --}}
        <div class="jtc-relblock jtc-relblock--rec">
            <div class="jtc-relblock__head">
                <h2 class="jtc-relblock__title">Recommended for you</h2>
            </div>
            <div class="jtc-rail">
                @foreach ($recommended as $p)
                    @include('storefront.partials.product-mini-card', ['p' => $p, 'rail' => true])
                @endforeach
            </div>
        </div>
    </div>

    {{-- toast --}}
    <div class="jtc-toast" :class="toastShow && 'is-open'" x-cloak>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span x-text="toastMsg"></span>
    </div>
</div>
@endsection
