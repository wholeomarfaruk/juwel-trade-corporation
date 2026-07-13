<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Category;
use App\Support\StorefrontData;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class DiscoverChipsSection extends Component
{
    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.discover-chips-section');
    }

    public function render()
    {
        $discoverChips = Category::where('homepage_category', true)
            ->orderBy('display_order', 'asc')
            ->pluck('name', 'slug')
            ->map(fn ($name, $slug) => [
                'name' => $name,
                'slug' => $slug,
                'url' => route('category.show', ['slug' => $slug]),            ]);
        return view('livewire.website.storefront.discover-chips-section', [
            'discoverChips' => $discoverChips
        ]);
    }
}
