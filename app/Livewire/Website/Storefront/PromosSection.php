<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Banner;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class PromosSection extends Component
{
    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.promos-section');
    }

    public function render()
    {
        $promos = Banner::zone(Banner::ZONE_PROMO_GRID)
            ->active()
            ->ordered()
            ->get()
            ->map(fn (Banner $banner) => [
                'image' => $banner->getImageUrl(),
                'link'  => $banner->link,
                'title' => $banner->title,
            ])
            ->filter(fn (array $banner) => $banner['image'])
            ->values();

        return view('livewire.website.storefront.promos-section', [
            'promos' => $promos,
        ]);
    }
}
