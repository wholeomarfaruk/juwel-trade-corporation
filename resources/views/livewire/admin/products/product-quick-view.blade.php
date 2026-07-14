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
                    @php $p = $this->product; @endphp

                    @if ($p->getImageThumbUrl())
                        <img src="{{ $p->getImageThumbUrl() }}" alt="{{ $p->name }}"
                            style="width:100%;max-height:260px;object-fit:contain;background:#f7faf8;border-radius:8px;margin-bottom:16px;">
                    @endif

                    <div class="body-title" style="font-size:18px;margin-bottom:4px;">{{ $p->name }}</div>
                    <div class="text-tiny" style="color:#6b7280;margin-bottom:14px;">
                        {{ $p->slug }}
                        @if ($p->sku) &nbsp;·&nbsp; SKU: {{ $p->sku }} @endif
                        @if ($p->brand?->name) &nbsp;·&nbsp; {{ $p->brand->name }} @endif
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:20px;margin-bottom:16px;">
                        <div>
                            <div class="text-tiny" style="color:#9ca3af;">Price</div>
                            @if ($p->discount_price && $p->discount_price > 0)
                                <div><del style="color:#9ca3af;">{{ $p->price }}</del> <strong>{{ $p->discount_price }}</strong></div>
                            @else
                                <div><strong>{{ $p->price }}</strong></div>
                            @endif
                        </div>
                        <div>
                            <div class="text-tiny" style="color:#9ca3af;">Stock</div>
                            <div>{{ $p->stock_status === 'in_stock' ? 'In stock' : 'Out of stock' }}</div>
                        </div>
                        <div>
                            <div class="text-tiny" style="color:#9ca3af;">Quantity</div>
                            <div>{{ $p->quantity }}</div>
                        </div>
                        @if ($p->weight)
                            <div>
                                <div class="text-tiny" style="color:#9ca3af;">Weight</div>
                                <div>{{ $p->weight }} kg</div>
                            </div>
                        @endif
                        <div>
                            <div class="text-tiny" style="color:#9ca3af;">Featured</div>
                            <div>{{ $p->featured ? 'Yes' : 'No' }}</div>
                        </div>
                    </div>

                    @if ($p->categories->isNotEmpty())
                        <div style="margin-bottom:12px;">
                            <div class="text-tiny" style="color:#9ca3af;margin-bottom:4px;">Categories</div>
                            <div>{{ $p->categories->pluck('name')->join(', ') }}</div>
                        </div>
                    @endif

                    @if ($p->sizes->isNotEmpty())
                        <div style="margin-bottom:12px;">
                            <div class="text-tiny" style="color:#9ca3af;margin-bottom:4px;">Sizes</div>
                            <div>{{ $p->sizes->pluck('name')->join(', ') }}</div>
                        </div>
                    @endif

                    @if ($p->short_description)
                        <div style="margin-bottom:12px;">
                            <div class="text-tiny" style="color:#9ca3af;margin-bottom:4px;">Short description</div>
                            <div>{!! $p->short_description !!}</div>
                        </div>
                    @endif

                    @if ($p->description)
                        <div>
                            <div class="text-tiny" style="color:#9ca3af;margin-bottom:4px;">Description</div>
                            <div style="max-height:180px;overflow-y:auto;">{!! $p->description !!}</div>
                        </div>
                    @endif
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
