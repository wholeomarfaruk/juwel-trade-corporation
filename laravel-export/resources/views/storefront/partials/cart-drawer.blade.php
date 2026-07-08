<div class="jtc-scrim" :class="(cartOpen || mmenuOpen) && 'is-open'" @click="closeAll()" x-cloak></div>

<aside class="jtc-cart" :class="cartOpen && 'is-open'" aria-label="Shopping cart">
    <div class="jtc-cart__head">
        <h3>Your cart</h3>
        <button class="jtc-cart__close" aria-label="Close cart" @click="closeAll()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
        </button>
    </div>

    <div class="jtc-cart__body">
        <div class="jtc-cart__empty" x-show="cartEmpty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" width="56" height="56"><circle cx="9" cy="21" r="1.6"></circle><circle cx="19" cy="21" r="1.6"></circle><path d="M2.5 3h2.2l2.1 12.1a1.8 1.8 0 0 0 1.8 1.5h9.1a1.8 1.8 0 0 0 1.8-1.4l1.6-7.2H6"></path></svg>
            <p>Your cart is empty.</p>
            <p>Add something to get started.</p>
        </div>

        <template x-for="l in cart" :key="l.id">
            <div class="jtc-cart__line">
                <img class="jtc-cart__thumb" :src="l.image" alt="">
                <div>
                    <div class="jtc-cart__name" x-text="l.name"></div>
                    <div class="jtc-cart__meta"><span x-text="linePrice(l)"></span> · SKU <span x-text="l.sku"></span></div>
                    <div class="jtc-cart__qty">
                        <button aria-label="Decrease" @click="setQty(l.id, -1)">−</button>
                        <span x-text="l.qty"></span>
                        <button aria-label="Increase" @click="setQty(l.id, 1)">+</button>
                    </div>
                </div>
                <div class="jtc-cart__lineright">
                    <span class="jtc-cart__linetotal" x-text="lineTotal(l)"></span>
                    <button class="jtc-cart__remove" @click="removeLine(l.id)">Remove</button>
                </div>
            </div>
        </template>
    </div>

    <div class="jtc-cart__foot" x-show="!cartEmpty" x-cloak>
        <div class="jtc-cart__row"><span>Subtotal</span><span x-text="cartSubtotal"></span></div>
        <div class="jtc-cart__row"><span>Delivery</span><span>Calculated at checkout</span></div>
        <div class="jtc-cart__total"><span>Total</span><span x-text="cartSubtotal"></span></div>
        <button class="jtc-btn jtc-btn--primary jtc-btn--block" style="padding:14px">Proceed to checkout</button>
        <p class="jtc-cart__note">Taxes &amp; delivery calculated at checkout</p>
    </div>
</aside>
