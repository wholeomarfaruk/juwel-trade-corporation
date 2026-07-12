<?php

namespace App\Livewire\Website\Storefront;

use App\Support\StorefrontData;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class BestSellersSection extends Component
{
    /** Visual variant for this section. One of: 'default' (rail), 'grid'. */
    public string $style = 'default';

    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.' . ($this->style === 'grid' ? 'product-grid' : 'product-rail'), [
            'title' => 'Best selling items',
        ]);
    }

    public function render()
    {
        return view('livewire.website.storefront.best-sellers-section', [
            'bestSellers' => StorefrontData::productsByTag('best'),
        ]);
    }
}
