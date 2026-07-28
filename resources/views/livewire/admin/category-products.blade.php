<div>
    <style>
        #category-products-sort-table .drag-handle {
            cursor: grab;
            color: #888;
            font-size: 18px;
            text-align: center;
        }

        #category-products-sort-table .drag-handle:active {
            cursor: grabbing;
        }

        #category-products-sort-table .sortable-ghost {
            opacity: 0.4;
            background: #e9f5ff;
        }

        #category-products-sort-table .sortable-drag {
            background: #fff;
        }

        #category-products-sort-table .sort-order-input {
            width: 80px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px 6px;
        }
    </style>

    <div class="wg-box">
        <div class="wg-table table-responsive">
            @if (Session::has('status'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('status') }}
                </div>
            @endif
            <div class="text-tiny mb-2">
                Drag rows by the handle to reorder within this page, or type a number in
                <strong>Sort Order</strong> to move a product to any position in this category. Changes save
                automatically.
            </div>
            <table class="table table-striped table-bordered" id="category-products-sort-table" wire:ignore.self>
                <thead>
                    <tr>
                        <th></th>
                        <th>Sort Order</th>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Quantity</th>
                        <th>Featured</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="category-products-sortable">
                    @forelse ($products as $pitem)
                        <tr wire:key="cat-product-{{ $pitem->id }}" data-id="{{ $pitem->id }}">
                            <td class="drag-handle" title="Drag to reorder">
                                <i class="icon-menu"></i>
                            </td>
                            <td>
                                <input type="number" class="sort-order-input"
                                    value="{{ $pitem->pivot->sort_order }}" min="1" step="1"
                                    wire:change.debounce.500ms="setPosition({{ $pitem->id }}, $event.target.value)">
                            </td>
                            <td>{{ $pitem->id }}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ $pitem->getImageThumbUrl() ?? '' }}" alt="{{ $pitem->name }}"
                                        class="image">
                                </div>
                                <div class="name">
                                    <a target="_blank"
                                        href="{{ route('product.show', ['slug' => $pitem->slug, 'segment' => $pitem->segment->slug]) }}"
                                        class="body-title-2">{{ $pitem->name }}</a>
                                    <div class="text-tiny mt-3">{{ $pitem->slug }}</div>
                                </div>
                            </td>
                            <td>{{ $pitem->price }}</td>
                            <td>{{ $pitem->sku }}</td>
                            <td>{{ $pitem->stock_status }}</td>
                            <td>{{ $pitem->quantity }}</td>
                            <td>{{ $pitem->featured == 1 ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="list-icon-function">
                                    <form action="{{ route('admin.categories.unassign.products', ['id' => $category->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="products" value="{{ $pitem->id }}">
                                        <div class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="divider"></div>
        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    @script
        <script>
            function loadCategoryProductsSortable(cb) {
                if (window.Sortable) return cb();
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';
                s.onload = cb;
                document.head.appendChild(s);
            }

            let categoryProductsSortableInstance = null;

            function initCategoryProductsSortable() {
                const el = document.getElementById('category-products-sortable');
                if (!el) return;

                if (categoryProductsSortableInstance && categoryProductsSortableInstance.el === el) return;
                if (categoryProductsSortableInstance) {
                    try { categoryProductsSortableInstance.destroy(); } catch (e) {}
                }

                categoryProductsSortableInstance = window.Sortable.create(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    onEnd: function() {
                        const ids = Array.from(el.querySelectorAll('tr[data-id]'))
                            .map(tr => tr.getAttribute('data-id'));
                        $wire.updateOrder(ids);
                    }
                });
            }

            loadCategoryProductsSortable(initCategoryProductsSortable);

            Livewire.hook('morphed', () => {
                categoryProductsSortableInstance = null;
                loadCategoryProductsSortable(initCategoryProductsSortable);
            });
        </script>
    @endscript
</div>
