<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Banner;
use App\Models\Slide;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class HeroSection extends Component
{
    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.hero-section');
    }

    public function render()
    {
        $slides = Slide::where('status', 1)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Slide $slide) => [
                'image' => $slide->getImageUrl(),
                'link'  => $slide->link,
                'title' => $slide->title,
            ])
            ->filter(fn (array $slide) => $slide['image'])
            ->values();

        $heroBanners = Banner::zone(Banner::ZONE_HERO_SIDE)
            ->active()
            ->ordered()
            ->take(2)
            ->get()
            ->map(fn (Banner $banner) => [
                'image' => $banner->getImageUrl(),
                'link'  => $banner->link,
                'title' => $banner->title,
            ])
            ->filter(fn (array $banner) => $banner['image'])
            ->values();

        return view('livewire.website.storefront.hero-section', [
            'slides'      => $slides,
            'heroBanners' => $heroBanners,
        ]);
    }
}
