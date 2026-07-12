<div>
    <div class="jtc-scrim" :class="(cartOpen || mmenuOpen) && 'is-open'" @click="closeAll()" x-cloak></div>

    <aside class="jtc-cart" :class="cartOpen && 'is-open'" aria-label="Shopping cart">
        <div class="jtc-cart__head">
            <h3>Your cart <span class="jtc-cart__count">({{ $cart->totalItems() }})</span></h3>
            <button class="jtc-cart__close" aria-label="Close cart" @click="closeAll()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
        </div>

        <div class="jtc-cart__body">
            @if ($cart->items->isEmpty())
                <div class="jtc-cart__empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" width="56" height="56"><circle cx="9" cy="21" r="1.6"></circle><circle cx="19" cy="21" r="1.6"></circle><path d="M2.5 3h2.2l2.1 12.1a1.8 1.8 0 0 0 1.8 1.5h9.1a1.8 1.8 0 0 0 1.8-1.4l1.6-7.2H6"></path></svg>
                    <p>Your cart is empty.</p>
                    <p>Add something to get started.</p>
                </div>
            @else
                @foreach ($cart->items as $item)
                    <div class="jtc-cart__line" wire:key="cart-line-{{ $item->id }}">
                        <img class="jtc-cart__thumb" src="{{ $item->product?->getImageFullUrl() ?? '' }}" alt="">
                        <div>
                            <div class="jtc-cart__name">{{ $item->product?->name ?? 'Unnamed product' }}</div>
                            <div class="jtc-cart__meta">৳{{ number_format($item->price, 2) }} · SKU {{ $item->product?->sku ?? '—' }}</div>
                            <div class="jtc-cart__qty">
                                <button aria-label="Decrease" wire:click="decreaseQty({{ $item->id }})">−</button>
                                <span>{{ $item->quantity }}</span>
                                <button aria-label="Increase" wire:click="increaseQty({{ $item->id }})">+</button>
                            </div>
                        </div>
                        <div class="jtc-cart__lineright">
                            <span class="jtc-cart__linetotal">৳{{ number_format($item->total, 2) }}</span>
                            <button class="jtc-cart__remove" wire:click="removeItem({{ $item->id }})">Remove</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @if ($cart->items->isNotEmpty())
            <div class="jtc-cart__foot">
                <div class="jtc-cart__row"><span>Subtotal</span><span>৳{{ number_format($cart->sub_total, 2) }}</span></div>
                <div class="jtc-cart__row"><span>Delivery</span><span>Calculated at checkout</span></div>
                <div class="jtc-cart__total"><span>Total</span><span>৳{{ number_format($cart->sub_total, 2) }}</span></div>
                <a href="{{ route('cart.checkout') }}" class="jtc-btn jtc-btn--primary jtc-btn--block" style="padding:14px">Proceed to checkout</a>
                <p class="jtc-cart__note">Taxes &amp; delivery calculated at checkout</p>
            </div>
        @endif
    </aside>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('add-to-cart-event', (event) => {
                const payload = event.payload;
                if (payload) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push(payload);
                }
            })
        })
    </script>
@endpush
