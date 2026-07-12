{{-- UI only for now — inputs are visually present but not yet wired to a
     filter controller. Functionality lands in a later pass. --}}
@php
    $bare = $bare ?? false;
    $activeCategoryId = $activeCategoryId ?? null;
@endphp

<div @class(['jtc-filters__card' => ! $bare])>
    @unless ($bare)
        <div class="jtc-filters__top">
            <h3>Filters</h3>
            <button type="button" class="jtc-filters__clear">Clear all</button>
        </div>
    @endunless

    {{-- Search --}}
    <div class="jtc-filters__search" @if($bare) style="margin-bottom:18px" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="search" placeholder="Search products…" aria-label="Search products">
    </div>

    {{-- Price --}}
    <div @class(['jtc-filters__group' => ! $bare])>
        <div class="jtc-filters__label">Price range</div>
        <div class="jtc-filters__price-inputs">
            <input type="number" min="0" placeholder="Min" aria-label="Minimum price">
            <span>–</span>
            <input type="number" min="0" placeholder="Max" aria-label="Maximum price">
        </div>
        <input type="range" min="0" max="200000" step="500" style="width:100%">
        <div class="jtc-filters__price-scale">
            <span>৳0</span>
            <strong>৳200,000</strong>
        </div>
    </div>

    {{-- Category --}}
    <div @class(['jtc-filters__group' => ! $bare]) @if($bare) style="margin-top:18px" @endif>
        <div class="jtc-filters__label">Category</div>
        <div class="jtc-filters__list">
            @forelse ($categories as $cat)
                <label class="jtc-filters__check">
                    <input type="checkbox" @checked($cat->id === $activeCategoryId) @disabled($cat->id === $activeCategoryId)>
                    {{ $cat->name }} <span>({{ $cat->products_count }})</span>
                </label>
            @empty
                <span class="jtc-filters__label" style="font-weight:400;color:var(--muted,#9aa8a1)">No categories yet.</span>
            @endforelse
        </div>
    </div>

    {{-- Brand --}}
    <div @class(['jtc-filters__group' => ! $bare]) @if($bare) style="margin-top:18px" @endif>
        <div class="jtc-filters__label">Brand</div>
        <div class="jtc-filters__list">
            @forelse ($brands as $brand)
                <label class="jtc-filters__check">
                    <input type="checkbox">
                    {{ $brand->name }} <span>({{ $brand->products_count }})</span>
                </label>
            @empty
                <span class="jtc-filters__label" style="font-weight:400;color:var(--muted,#9aa8a1)">No brands yet.</span>
            @endforelse
        </div>
    </div>
</div>
