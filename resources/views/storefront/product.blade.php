@extends('layouts.app')
@section('segment', $segment)

@section('page_title', ($product->name ?? 'Product') . ' | ' . ($site['site_name'] ?? 'Juwel Trade Corporation'))
@push('meta')
    @php
        $_sn       = $site['site_name'] ?? 'Juwel Trade Corporation';
        $metaName  = $product->name ?? 'Product';
        $metaDesc  = $product->short_description ?? $product->description ?? 'Buy ' . $metaName . ' from ' . $_sn . '. Premium quality products at the best price.';
        $metaDesc  = \Illuminate\Support\Str::limit(strip_tags((string) $metaDesc), 155);
        $metaFb    = !empty($site['favicon']) ? asset('storage/'.$site['favicon']) : asset('images/jtc-logo.jpeg');
        $metaImage = $product->image ? asset('storage/images/products/' . $product->image) : $metaFb;
        $metaUrl   = url()->current();
        $metaPrice = $product->discount_price ?? $product->price ?? null;
    @endphp
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $metaUrl }}">

    <meta property="og:type"        content="product">
    <meta property="og:title"       content="{{ $metaName }} | {{ $_sn }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:image"       content="{{ $metaImage }}">
    <meta property="og:url"         content="{{ $metaUrl }}">
    <meta property="og:site_name"   content="{{ $_sn }}">
    @if ($metaPrice)
        <meta property="product:price:amount"   content="{{ $metaPrice }}">
        <meta property="product:price:currency" content="BDT">
    @endif

    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $metaName }} | {{ $_sn }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image"       content="{{ $metaImage }}">
@endpush

@php
    $isCompare  = $product->discount_price && (float) $product->discount_price > 0 && (float) $product->discount_price < (float) $product->price;
    $displayPrice = $isCompare ? $product->discount_price : $product->price;
    $pct        = $isCompare ? (int) round((1 - $product->discount_price / $product->price) * 100) : 0;
    $money      = fn ($n) => '৳' . number_format((float) $n);
    $outOfStock = $product->stock_status === 'out_of_stock';
    $firstCategory = $product->categories->first();

    $fancyItems = collect($gallery)->map(fn ($src) => ['src' => $src])
        ->when($videoSrc, fn ($c) => $c->push(['src' => $videoSrc]))
        ->values();
@endphp

@section('content')
<div class="jtc-pd__shell" x-data="{ qty: 1, activeMedia: 0, items: @js($fancyItems),
    inc() { this.qty += 1; }, dec() { this.qty = Math.max(1, this.qty - 1); },
    openLightbox() {
        if (window.Fancybox) {
            Fancybox.show(this.items, { startIndex: this.activeMedia });
        }
    },
    buyNow(productId, quantity) {
        const onUpdate = () => { window.location.href = @js(route('cart.checkout')); window.removeEventListener('cart-updated', onUpdate); };
        window.addEventListener('cart-updated', onUpdate);
        window.dispatchEvent(new CustomEvent('add-to-cart', { detail: { productId, quantity } }));
    } }">
    <div class="jtc-pd__crumb">
        <a href="{{ route('home') }}">Home</a><span class="sep">/</span>
        <a href="{{ route('shop') }}">Shop</a><span class="sep">/</span>
        @if ($firstCategory)
            <a href="{{ route('category.show', $firstCategory->slug) }}">{{ $firstCategory->name }}</a><span class="sep">/</span>
        @endif
        <span style="color:#14201c;font-weight:600">{{ $product->name }}</span>
    </div>

    {{-- MAIN --}}
    <div class="jtc-pd__main">

        {{-- gallery --}}
        <div class="jtc-gallery">
            <div class="jtc-gallery__thumbs">
                @foreach ($gallery as $i => $src)
                    <button type="button" class="jtc-gallery__thumb" :class="activeMedia === {{ $i }} && 'is-active'" @click="activeMedia = {{ $i }}" aria-label="View image">
                        <img src="{{ $src }}" alt="">
                    </button>
                @endforeach
                @if ($videoSrc)
                    <button type="button" class="jtc-gallery__thumb" :class="activeMedia === {{ count($gallery) }} && 'is-active'" @click="activeMedia = {{ count($gallery) }}" aria-label="View video">
                        <img src="{{ $gallery[0] ?? '' }}" alt="">
                        <span class="jtc-gallery__play"><svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M8 5v14l11-7z"></path></svg></span>
                    </button>
                @endif
            </div>

            <div class="jtc-gallery__viewer" @click="openLightbox()">
                @if ($videoSrc)
                    <template x-if="activeMedia === {{ count($gallery) }}">
                        <div class="jtc-gallery__videoposter">
                            <img src="{{ $gallery[0] ?? '' }}" alt="{{ $product->name }}">
                            <span class="jtc-gallery__play jtc-gallery__play--lg"><svg viewBox="0 0 24 24" fill="currentColor" width="28" height="28"><path d="M8 5v14l11-7z"></path></svg></span>
                        </div>
                    </template>
                @endif
                @foreach ($gallery as $i => $src)
                    <template x-if="activeMedia === {{ $i }}">
                        <img src="{{ $src }}" alt="{{ $product->name }}">
                    </template>
                @endforeach
                <div class="jtc-gallery__badges" @if ($videoSrc) x-show="activeMedia !== {{ count($gallery) }}" @endif>
                    @if ($outOfStock)
                        <span class="jtc-badge jtc-badge--outofstock">Out of stock</span>
                    @endif
                    @if ($isCompare)
                        <span class="jtc-badge jtc-badge--deal">-{{ $pct }}%</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- info --}}
        <div class="jtc-pd-info">
            <div class="jtc-pd-info__brandrow">
                @if ($product->brand?->name)
                    <span class="jtc-pd-info__brand">{{ $product->brand->name }}</span>
                    <span class="jtc-pd-info__dot"></span>
                @endif
                @if ($product->sku)
                    <span class="jtc-pd-info__sku">SKU: {{ $product->sku }}</span>
                @endif
            </div>

            <h1 class="jtc-pd-info__title">{{ $product->name }}</h1>

            <div class="jtc-pd-info__meta">
                @if ($outOfStock)
                    <span class="jtc-pd-info__stock jtc-pd-info__stock--out">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Out of stock
                    </span>
                @else
                    <span class="jtc-pd-info__stock">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" width="14" height="14"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        In stock
                    </span>
                @endif
            </div>

            <div class="jtc-pd-info__price">
                <span class="jtc-pd-info__price-now">{{ $money($displayPrice) }}</span>
                @if ($isCompare)
                    <span class="jtc-pd-info__price-was">{{ $money($product->price) }}</span>
                    <span class="jtc-pd-info__save">Save {{ $money($product->price - $displayPrice) }}</span>
                @endif
            </div>

            @if ($product->short_description)
                <div class="jtc-pd-info__short">{!! $product->short_description !!}</div>
            @endif

            @if ($product->sizes->count() > 0)
                <div class="jtc-pd-info__highlights">
                    <label class="jtc-pd-info__brand" style="display:block;margin-bottom:8px">Select size</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($product->sizes as $size)
                            <span class="jtc-badge" style="background:#fff;color:#14201c;border:1.5px solid #e6eae7">{{ $size->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="jtc-pd-info__buyrow">
                <div class="jtc-qty">
                    <button type="button" @click="dec()" :disabled="{{ $outOfStock ? 'true' : 'false' }}" aria-label="Decrease">−</button>
                    <span x-text="qty"></span>
                    <button type="button" @click="inc()" :disabled="{{ $outOfStock ? 'true' : 'false' }}" aria-label="Increase">+</button>
                </div>
                <button type="button" class="jtc-pd-actions__cart" {{ $outOfStock ? 'disabled' : '' }}
                        @click="$dispatch('add-to-cart', { productId: {{ $product->id }}, quantity: qty }); cartOpen = true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M6 6h15l-1.5 9h-12z"></path><circle cx="9" cy="20" r="1.4"></circle><circle cx="18" cy="20" r="1.4"></circle></svg>
                    Add to cart
                </button>
            </div>

            <button type="button" class="jtc-pd-actions__buy" {{ $outOfStock ? 'disabled' : '' }}
                    @click="buyNow({{ $product->id }}, qty)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M13 2 3 14h7v8l10-12h-7z"></path></svg>
                {{ $outOfStock ? 'Out of stock' : 'Buy now' }}
            </button>

            <div class="jtc-pd-actions__order">
                <a href="https://wa.me/{{ $site['whatsapp'] ?? '8801329732724' }}?text={{ urlencode('Hi, I want to order: ' . $product->name) }}" target="_blank" rel="noopener" class="jtc-pd-actions__wa">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="19" height="19"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.52 11.99c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"></path></svg>
                    Order on WhatsApp
                </a>
                <a href="tel:+{{ $site['phone'] ?? '8801329732724' }}" class="jtc-pd-actions__call">
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

    {{-- description --}}
    @if ($product->description)
        <div class="jtc-pd-desc">
            {!! $product->description !!}
        </div>
    @endif

    {{-- RELATED --}}
    @if ($related->isNotEmpty())
        <div class="jtc-relblock">
            <div class="jtc-relblock__head">
                <h2 class="jtc-relblock__title">Related products</h2>
                <a href="{{ route('shop') }}" class="jtc-relblock__more">View more
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
            <div class="jtc-shopgrid">
                @foreach ($related as $p)
                    @include('storefront.partials.product-card', ['p' => $p, 'rail' => false])
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
    @if (isset($viewItemEventPayload))
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({ ecommerce: null });
            const viewItemEventPayload = @json($viewItemEventPayload);
            if (viewItemEventPayload) {
                window.dataLayer.push(viewItemEventPayload);
            }
        </script>
    @endif
@endpush
