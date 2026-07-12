<section class="jtc-section jtc-section--tight">
    <div class="jtc-shell">
        <div style="margin-bottom:26px"><h2 class="jtc-h2">Discover more</h2></div>
        <div class="jtc-chips">
            @foreach ($discoverChips as $chip)
                <a href="#" class="jtc-chip">{{ $chip }}</a>
            @endforeach
        </div>
    </div>
</section>
