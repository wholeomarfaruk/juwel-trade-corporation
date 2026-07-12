<?php

namespace App\Livewire\Website\Storefront;

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
        return view('livewire.website.storefront.discover-chips-section', [
            'discoverChips' => StorefrontData::discoverChips(),
        ]);
    }
}
