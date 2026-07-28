<div>
    <style>
        #products-sort-table .drag-handle {
            cursor: grab;
            color: #888;
            font-size: 18px;
            text-align: center;
        }

        #products-sort-table .drag-handle:active {
            cursor: grabbing;
        }

        #products-sort-table .sortable-ghost {
            opacity: 0.4;
            background: #e9f5ff;
        }

        #products-sort-table .sortable-drag {
            background: #fff;
        }

        #products-sort-table .sort-order-input {
            width: 80px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px 6px;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Products</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All Products</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input wire:model.live="search" type="text" placeholder="Search here..." class=""
                                    name="search" tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.products.add') }}"><i
                            class="icon-plus"></i>Add new</a>
                </div>
                <div class="table-responsive">
                    @if (Session::has('status'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('status') }}
                        </div>
                    @endif
                    <div class="text-tiny mb-2">
                        Drag rows by the handle to reorder within this page, or type a number in
                        <strong>Sort Order</strong> to move a product to any global position. Changes save
                        automatically.
                    </div>
                    <table class="table table-striped table-bordered" id="products-sort-table" wire:ignore.self>
                        <thead>

                            <tr>
                                <th></th>
                                <th>Sort Order</th>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                <th>Views</th>
                                <th>Featured</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="products-sortable">
                            @foreach ($products as $product)
                                <tr wire:key="product-{{ $product->id }}" data-id="{{ $product->id }}">
                                    <td class="drag-handle" title="Drag to reorder">
                                        <i class="icon-menu"></i>
                                    </td>
                                    <td>
                                        <input type="number" class="sort-order-input"
                                            value="{{ $product->sort_order }}" min="1" step="1"
                                            wire:change.debounce.500ms="setPosition({{ $product->id }}, $event.target.value)">
                                    </td>
                                    <td>{{ $product->id }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            @if ($product->getImageThumbUrl())
                                                <img src="{{ $product->getImageThumbUrl() }}"
                                                    alt="{{ $product->name }}" class="image">
                                            @endif
                                        </div>
                                        <div class="name">
                                            <a target="_blank"
                                                href="{{ route('product.show', ['slug' => $product->slug, 'segment' => $product->segment]) }}"
                                                class="body-title-2">{{ $product->name }}</a>
                                            <div class="text-tiny mt-3">{{ $product->slug }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($product->discount_price && $product->discount_price > 0)
                                            <del> {{ $product->price }}</del> <span> -
                                                {{ $product->discount_price }}</span>
                                        @else
                                            {{ $product->price }}
                                        @endif
                                    </td>

                                    <td>{{ $product->stock_status }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->views }}</td>
                                    <td>{{ $product->featured == 1 ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            <!-- Default dropstart button -->
                                            <div class="btn-group dropstart">
                                                <button type="button" class="btn btn-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button type="button" class="dropdown-item"
                                                            onclick="Livewire.dispatch('open-product-quick-view', { productId: {{ $product->id }} })">Quick
                                                            view</button>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.products.edit', ['id' => $product->id]) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.products.copy', ['id' => $product->id]) }}">Copy
                                                            Product</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.products.delete', ['id' => $product->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="dropdown-item text-danger delete">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                    {{ $products->links('pagination::bootstrap-5') }}

                </div>
            </div>
        </div>
    </div>
    @livewire('admin.products.product-quick-view')

    @script
        <script>
            // Load SortableJS once, then keep the table sortable across Livewire re-renders.
            function loadSortable(cb) {
                if (window.Sortable) return cb();
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';
                s.onload = cb;
                document.head.appendChild(s);
            }

            let sortableInstance = null;

            function initProductsSortable() {
                const el = document.getElementById('products-sortable');
                if (!el) return;

                // Avoid stacking multiple instances on the same element after a morph.
                if (sortableInstance && sortableInstance.el === el) return;
                if (sortableInstance) {
                    try { sortableInstance.destroy(); } catch (e) {}
                }

                sortableInstance = window.Sortable.create(el, {
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

            loadSortable(initProductsSortable);

            // Re-attach after Livewire updates the DOM (search, pagination, reorder).
            Livewire.hook('morphed', () => {
                sortableInstance = null;
                loadSortable(initProductsSortable);
            });
        </script>
    @endscript
</div>
