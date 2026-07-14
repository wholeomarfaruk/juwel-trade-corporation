<?php

namespace App\Livewire\Admin\Products;

use App\Models\products;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductQuickView extends Component
{
    public bool $isOpen = false;
    public ?int $productId = null;

    #[On('open-product-quick-view')]
    public function open(int $productId): void
    {
        $this->productId = $productId;
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->productId = null;
    }

    public function getProductProperty()
    {
        if (!$this->productId) {
            return null;
        }

        return products::with(['brand', 'categories', 'sizes'])->find($this->productId);
    }

    public function render()
    {
        return view('livewire.admin.products.product-quick-view');
    }
}
