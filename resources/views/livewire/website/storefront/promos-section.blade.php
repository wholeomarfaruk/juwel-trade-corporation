<div>
@if ($promos->isNotEmpty())
    <section class="jtc-section jtc-section--tight">
        <div class="jtc-shell">
            <div class="jtc-promos">
                @foreach ($promos as $promo)
                    <a href="{{ $promo['link'] ?: '#' }}" class="jtc-promo">
                        <img src="{{ $promo['image'] }}" alt="{{ $promo['title'] }}" loading="lazy">
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
</div>
