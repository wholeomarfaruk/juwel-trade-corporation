<?php

namespace App\Livewire\Website\Storefront;

use App\Models\products;
use App\Support\StorefrontData;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class BrowseAllSection extends Component
{
    public int $limit = 12;

    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.product-grid');
    }

    public function render()
    {
        $browseAll = products::where('status', 1)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take($this->limit)
            ->get()
            ->map(fn ($product) => StorefrontData::decorateEloquentProduct($product));

        return view('livewire.website.storefront.browse-all-section', [
            'browseAll' => $browseAll,
        ]);
    }
}
