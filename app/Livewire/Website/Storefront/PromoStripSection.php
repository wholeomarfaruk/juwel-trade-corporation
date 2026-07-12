<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Banner;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class PromoStripSection extends Component
{
    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.promo-strip-section');
    }

    public function render()
    {
        $banner = Banner::zone(Banner::ZONE_PROMO_STRIP)
            ->active()
            ->ordered()
            ->first();

        return view('livewire.website.storefront.promo-strip-section', [
            'banner' => $banner,
        ]);
    }
}
