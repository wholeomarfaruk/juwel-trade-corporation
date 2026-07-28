<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryProducts extends Component
{
    use WithPagination;

    public int $categoryId;

    protected $paginationTheme = 'bootstrap';

    public function mount(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function render()
    {
        $category = Category::findOrFail($this->categoryId);

        $products = $category->products()->paginate(20);

        // See App\Livewire\Admin\Products::render() for why this is forced.
        $products->withPath(route('admin.categories.manage.products', ['id' => $this->categoryId], false));

        return view('livewire.admin.category-products', compact('category', 'products'));
    }

    /**
     * Persist a drag reorder of the rows on the current page, within this category only.
     * Receives an ordered list of product ids and assigns each the sort_order
     * of the slot it now occupies, keeping the page's existing slots so the
     * category's global order stays consistent.
     */
    public function updateOrder($orderedIds)
    {
        $orderedIds = collect($orderedIds)->map(fn ($id) => (int) $id)->filter()->values();

        $slots = DB::table('product_category')
            ->where('category_id', $this->categoryId)
            ->whereIn('products_id', $orderedIds)
            ->orderBy('sort_order')
            ->orderByDesc('products_id')
            ->pluck('sort_order')
            ->values();

        foreach ($orderedIds as $index => $productId) {
            $slot = $slots[$index] ?? $index;
            DB::table('product_category')
                ->where('category_id', $this->categoryId)
                ->where('products_id', $productId)
                ->update(['sort_order' => $slot]);
        }
    }

    /**
     * Move a product to an absolute position within this category via the number input.
     * position is 1-based across the whole category, so the admin can jump
     * a product across pages.
     */
    public function setPosition($productId, $position)
    {
        $position = max(1, (int) $position);

        $exists = DB::table('product_category')
            ->where('category_id', $this->categoryId)
            ->where('products_id', $productId)
            ->exists();
        if (!$exists) {
            return;
        }

        $ids = DB::table('product_category')
            ->where('category_id', $this->categoryId)
            ->where('products_id', '!=', $productId)
            ->orderBy('sort_order')
            ->orderByDesc('products_id')
            ->pluck('products_id')
            ->values();

        $position = min($position, $ids->count() + 1);
        $ids->splice($position - 1, 0, $productId);

        foreach ($ids as $index => $pid) {
            DB::table('product_category')
                ->where('category_id', $this->categoryId)
                ->where('products_id', $pid)
                ->update(['sort_order' => $index + 1]);
        }

        $this->resetPage();
    }
}
