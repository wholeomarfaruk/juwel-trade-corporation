{{--
    ProductQuickView — reusable product details modal for the admin products list.

    Usage in any admin page:
        @livewire('admin.products.product-quick-view')

    Open via Livewire event:
        Livewire.dispatch('open-product-quick-view', { productId: 12 })
--}}

<div x-data="{ open: @entangle('isOpen').live }" x-cloak>
    <style>
        @keyframes pqv-shimmer { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
        .pqv-skel {
            border-radius: 6px;
            background: linear-gradient(90deg, #f2f5f4 25%, #eef2f0 37%, #f2f5f4 63%);
            background-size: 800px 100%;
            animation: pqv-shimmer 1.4s ease-in-out infinite;
        }
    </style>

    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;"
        @click="$wire.close()"
    ></div>

    {{-- Modal panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="
            position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
            z-index:1050; width:min(94vw,640px); max-height:88vh; overflow-y:auto;
            background:#fff; border-radius:12px; display:flex; flex-direction:column;
            box-shadow:0 20px 60px rgba(0,0,0,.25);
        "
    >
        {{-- Header --}}
        <div style="padding:14px 20px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; gap:10px;">
            <div class="body-title">Product quick view</div>
            <button type="button" wire:click="close"
                style="background:none;border:none;font-size:20px;cursor:pointer;color:#6b7280;line-height:1;"
                aria-label="Close">&times;</button>
        </div>

        <div style="padding:20px;">
            {{-- Skeleton — shown while the product is loading --}}
            <div wire:loading wire:target="open">
                <div class="pqv-skel" style="width:100%;aspect-ratio:1/1;max-height:220px;margin-bottom:16px;"></div>
                <div class="pqv-skel" style="height:20px;width:70%;margin-bottom:10px;"></div>
                <div class="pqv-skel" style="height:14px;width:40%;margin-bottom:18px;"></div>
                <div class="pqv-skel" style="height:16px;width:30%;margin-bottom:10px;"></div>
                <div class="pqv-skel" style="height:14px;width:90%;margin-bottom:6px;"></div>
                <div class="pqv-skel" style="height:14px;width:85%;"></div>
            </div>

            {{-- Data — shown once loaded --}}
            <div wire:loading.remove wire:target="open">
                @if ($this->product)
                    @php
                        $p = $this->product;
                        $na = '<span style="color:#9ca3af;">N/A</span>';

                        $rows = [
                            'Name'               => e($p->name),
                            'Slug'               => e($p->slug),
                            'SKU'                => $p->sku ? e($p->sku) : $na,
                            'Brand'              => $p->brand?->name ? e($p->brand->name) : $na,
                            'Price'              => $p->price !== null ? e($p->price) : $na,
                            'Discount price'     => $p->discount_price && $p->discount_price > 0 ? e($p->discount_price) : $na,
                            'Purchase price'     => $p->purchase_price !== null ? e($p->purchase_price) : $na,
                            'Stock'              => $p->stock_status === 'in_stock' ? 'In stock' : ($p->stock_status === 'out_of_stock' ? 'Out of stock' : 'N/A'),
                            'Quantity'           => $p->quantity ?? 'N/A',
                            'Weight'             => $p->weight !== null ? e($p->weight) . ' kg' : $na,
                            'Views'              => $p->views ?? 'N/A',
                            'Featured'           => $p->featured ? 'Yes' : 'No',
                            'Status'             => $p->status ? 'Active' : 'Inactive',
                            'Categories'         => $p->categories->isNotEmpty() ? e($p->categories->pluck('name')->join(', ')) : $na,
                            'Sizes'              => $p->sizes->isNotEmpty() ? e($p->sizes->pluck('name')->join(', ')) : $na,
                            'Short description'  => $p->short_description ?: $na,
                            'Description'        => $p->description ?: $na,
                        ];
                    @endphp

                    @if ($p->getImageThumbUrl())
                        <img src="{{ $p->getImageThumbUrl() }}" alt="{{ $p->name }}"
                            style="width:100%;max-height:260px;object-fit:contain;background:#f7faf8;border-radius:8px;margin-bottom:16px;">
                    @else
                        <div style="width:100%;height:180px;display:flex;align-items:center;justify-content:center;background:#f7faf8;border-radius:8px;margin-bottom:16px;color:#9ca3af;">N/A</div>
                    @endif

                    <table style="width:100%;border-collapse:collapse;">
                        <tbody>
                            @foreach ($rows as $label => $value)
                                <tr style="border-bottom:1px solid #f0f1f3;">
                                    <td style="padding:9px 12px 9px 0;color:#9ca3af;font-size:12.5px;white-space:nowrap;vertical-align:top;width:160px;">{{ $label }}</td>
                                    <td style="padding:9px 0;font-size:13.5px;color:#111827;{{ in_array($label, ['Short description', 'Description']) ? 'max-height:160px;overflow-y:auto;display:block;' : '' }}">{!! $value !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        @if ($this->product)
            <div style="padding:14px 20px; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:10px;">
                <a href="{{ route('admin.products.edit', ['id' => $this->product->id]) }}" class="tf-button style-1">Edit product</a>
            </div>
        @endif
    </div>
</div>
