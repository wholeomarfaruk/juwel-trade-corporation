@php $bare = $bare ?? false; @endphp

<div @class(['jtc-filters__card' => ! $bare])>
    @unless ($bare)
        <div class="jtc-filters__top">
            <h3>Filters</h3>
            <button class="jtc-filters__clear" @click="clearFilters()">Clear all</button>
        </div>
    @endunless

    {{-- Search --}}
    <div class="jtc-filters__search" @if($bare) style="margin-bottom:18px" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="search" x-model="search" placeholder="Search products…" aria-label="Search products">
    </div>

    {{-- Price --}}
    <div @class(['jtc-filters__group' => ! $bare])>
        <div class="jtc-filters__label">Price range</div>
        <div class="jtc-filters__price-inputs">
            <input type="number" min="0" :max="priceCap" x-model.number="priceMin" placeholder="Min" aria-label="Minimum price">
            <span>–</span>
            <input type="number" min="0" :max="priceCap" x-model.number="priceMax" placeholder="Max" aria-label="Maximum price">
        </div>
        <input type="range" min="0" :max="priceCap" step="500" x-model.number="priceMax" style="width:100%">
        <div class="jtc-filters__price-scale">
            <span x-text="money(priceMin)"></span>
            <strong x-text="money(priceMax)"></strong>
        </div>
    </div>

    {{-- Category --}}
    <div @class(['jtc-filters__group' => ! $bare]) @if($bare) style="margin-top:18px" @endif>
        <div class="jtc-filters__label">Category</div>
        <div class="jtc-filters__list">
            @foreach ($categories as $cat)
                <label class="jtc-filters__check">
                    <input type="checkbox" :checked="selectedCats.includes(@js($cat['slug']))" @change="toggleCat(@js($cat['slug']))">
                    {{ $cat['name'] }} <span x-text="'(' + catCount(@js($cat['slug'])) + ')'"></span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Brand --}}
    <div @class(['jtc-filters__group' => ! $bare]) @if($bare) style="margin-top:18px" @endif>
        <div class="jtc-filters__label">Brand</div>
        <div class="jtc-filters__list">
            @foreach ($brands as $b)
                <label class="jtc-filters__check">
                    <input type="checkbox" :checked="selectedBrands.includes(@js($b['name']))" @change="toggleBrand(@js($b['name']))">
                    {{ $b['name'] }} <span x-text="'(' + brandCount(@js($b['name'])) + ')'"></span>
                </label>
            @endforeach
        </div>
    </div>
</div>
