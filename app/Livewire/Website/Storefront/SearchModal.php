<?php

namespace App\Livewire\Website\Storefront;

use App\Support\StorefrontData;
use Livewire\Component;

class SearchModal extends Component
{
    public string $query = '';
    public ?string $category = null;

    public function selectCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getResultsProperty()
    {
        return StorefrontData::searchProducts($this->query, $this->category);
    }

    public function render()
    {
        return view('livewire.website.storefront.search-modal', [
            'results'    => $this->results,
            'categories' => StorefrontData::categories(),
        ]);
    }
}
