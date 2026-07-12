{{-- Skeleton for a grid-style product section. Optional: $title --}}
<section class="jtc-section">
    <div class="jtc-shell">
        <div class="jtc-skel-head">
            @if (! empty($title))
                <h2 class="jtc-h2">{{ $title }}</h2>
            @else
                <div class="jtc-skel-head__title"></div>
            @endif
            <div class="jtc-skel-head__link"></div>
        </div>
        <div class="jtc-grid">
            @for ($i = 0; $i < 12; $i++)
                <div class="jtc-skel-card">
                    <div class="jtc-skel-card__media"></div>
                    <div class="jtc-skel-card__body">
                        <div class="jtc-skel-card__title"></div>
                        <div class="jtc-skel-card__title2"></div>
                        <div class="jtc-skel-card__price"></div>
                        <div class="jtc-skel-card__btn"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
