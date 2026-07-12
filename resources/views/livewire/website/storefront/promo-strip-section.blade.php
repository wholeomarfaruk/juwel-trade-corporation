<div>
@if ($banner && $banner->getImageUrl())
    <section class="jtc-section jtc-section--strip">
        <div class="jtc-shell">
            <a href="{{ $banner->link ?: '#' }}" class="jtc-promo" style="height:100px;min-height:0">
                <img src="{{ $banner->getImageUrl() }}" alt="{{ $banner->title }}">
            </a>
        </div>
    </section>
@endif
</div>
