<?php

namespace App\Livewire\Admin;

use App\Models\products as ModelsProducts;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = ModelsProducts::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%')
                    ->orWhere('price', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('id')
            ->paginate(20);

        // Force pagination links to build from the named route rather than the
        // ambient request URL, so a misconfigured document root / proxy prefix
        // on the live server can't get doubled into page links (e.g. /admin/admin/products).
        $products->withPath(route('admin.products', [], false));

        return view('livewire.admin.products', compact('products'));
    }

    /**
     * Persist a drag reorder of the rows on the current page.
     * Receives an ordered list of product ids and assigns each the
     * sort_order of the slot it now occupies, keeping the page's
     * existing slots so the global order stays consistent.
     */
    public function updateOrder($orderedIds)
    {
        $orderedIds = collect($orderedIds)->map(fn ($id) => (int) $id)->filter()->values();

        $slots = ModelsProducts::whereIn('id', $orderedIds)
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('id')
            ->pluck('sort_order')
            ->values();

        foreach ($orderedIds as $index => $id) {
            $slot = $slots[$index] ?? $index;
            ModelsProducts::where('id', $id)->update(['sort_order' => $slot]);
        }
    }

    /**
     * Move a product to an absolute global position via the number input.
     * position is 1-based across the whole product list, so the admin can
     * jump a product across pages.
     */
    public function setPosition($id, $position)
    {
        $position = max(1, (int) $position);
        $product = ModelsProducts::find($id);
        if (!$product) {
            return;
        }

        $ids = ModelsProducts::where('id', '!=', $id)
            ->orderBy('sort_order', 'asc')
            ->orderByDesc('id')
            ->pluck('id')
            ->values();

        $position = min($position, $ids->count() + 1);
        $ids->splice($position - 1, 0, $product->id);

        foreach ($ids as $index => $pid) {
            ModelsProducts::where('id', $pid)->update(['sort_order' => $index + 1]);
        }

        $this->resetPage();
    }
}
