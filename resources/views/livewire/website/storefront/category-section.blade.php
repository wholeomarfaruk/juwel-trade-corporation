<div>
@if ($category && $categoryProducts->isNotEmpty())
    @if ($style === 'compact')
        {{-- Compact: tighter head, smaller rail --}}
        <section class="jtc-section jtc-section--tight">
            <div class="jtc-shell">
                <div class="jtc-section-head">
                    <h2 class="jtc-h2" style="font-size:1.3rem">{{ $category->name ?? '' }}</h2>
                    @if ($category->slug ?? null)
                        <a href="{{ route('category.show', $category->slug) }}" class="jtc-seeall">See all @include('storefront.partials.icons.arrow')</a>
                    @endif
                </div>
                <div class="{{ $rail ? 'jtc-rail' : 'jtc-grid' }}">
                    @foreach ($categoryProducts as $p)
                        @include('storefront.partials.product-card', ['p' => $p, 'rail' => $rail])
                    @endforeach
                </div>
            </div>
        </section>
    @else
        {{-- Default --}}
        <section class="jtc-section">
            <div class="jtc-shell">
                <div class="jtc-section-head">
                    <h2 class="jtc-h2">{{ $category->name ?? '' }}</h2>
                    @if ($category->slug ?? null)
                        <a href="{{ route('category.show', $category->slug) }}" class="jtc-seeall">See all @include('storefront.partials.icons.arrow')</a>
                    @endif
                </div>
                <div class="{{ $rail ? 'jtc-rail' : 'jtc-grid' }}">
                    @foreach ($categoryProducts as $p)
                        @include('storefront.partials.product-card', ['p' => $p, 'rail' => $rail])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@else
    <div></div>
@endif
</div>
