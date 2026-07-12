<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Category;
use App\Support\StorefrontData;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class CategorySection extends Component
{
    public ?int $category_id = null;
    public int $limit = 12;
    public bool $rail = true;

    /** Visual variant for this section. One of: 'default', 'compact', 'banner'. */
    public string $style = 'default';

    public function placeholder()
    {
        return view('livewire.website.storefront.placeholders.' . ($this->rail ? 'product-rail' : 'product-grid'));
    }

    public function render()
    {
        $category = $this->category_id ? Category::find($this->category_id) : null;

        if (! $category) {
            return view('livewire.website.storefront.category-section', [
                'category'         => null,
                'categoryProducts' => collect(),
            ]);
        }

        $products = $category->products()
            ->where('status', 1)
            ->orderByDesc('featured')
            ->orderByDesc('created_at')
            ->take($this->limit)
            ->get();

        return view('livewire.website.storefront.category-section', [
            'category'         => $category,
            'categoryProducts' => $products->map(fn ($product) => StorefrontData::decorateEloquentProduct($product)),
        ]);
    }
}
