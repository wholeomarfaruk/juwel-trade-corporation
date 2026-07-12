<div>
     <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-1">Checkout</h1>
                            <p class="text-muted mb-4">Complete your shipping and payment details to place the order.</p>

                            @if ($savedAddresses->isNotEmpty())
                                <div class="mb-4">
                                    <label class="form-label">Use a saved address</label>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($savedAddresses as $saved)
                                            <label class="border rounded-3 p-3 d-flex justify-content-between align-items-start gap-2" style="cursor:pointer">
                                                <span>
                                                    <strong>{{ $saved->name }}</strong>
                                                    @if ($saved->is_primary)
                                                        <span class="badge bg-primary-subtle text-primary ms-1">Primary</span>
                                                    @endif
                                                    <small class="d-block text-muted">{{ $saved->phone }}</small>
                                                    <small class="d-block text-muted">{{ $saved->address }}</small>
                                                </span>
                                                <input type="radio"
                                                    name="saved_address"
                                                    class="form-check-input flex-shrink-0"
                                                    wire:click="selectAddress({{ $saved->id }})"
                                                    {{ $selectedAddressId === $saved->id ? 'checked' : '' }}>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <form wire:submit.prevent="place_order" action="{{ route('cart.checkout.order.place') }}" method="POST" novalidate >
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Enter your name</label>
                                        <input type="text"
                                        wire:model="name"
                                            id="full_name"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Enter your full name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Enter your mobile number</label>
                                        <input type="text"
                                        wire:model="phone"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            placeholder="Enter your phone number"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="col-12">
                                        <label for="address" class="form-label">Enter your full address</label>
                                        <textarea id="address"
                                        wire:model="address"
                                            name="address"
                                            rows="3"
                                            placeholder="Enter your full delivery address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="note" class="form-label">Enter your note (optional)</label>
                                        <textarea id="note"
                                        wire:model="note"
                                            name="note"
                                            rows="3"
                                            placeholder="Enter your custom note"
                                            class="form-control @error('address') is-invalid @enderror">{{ old('note') }}</textarea>
                                        @error('note')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                       <div class="col-12">
                                        <label for="delivery_area_id" class="form-label">Select delivery area</label>
                                        <select wire:model.live="delivery_area" name="delivery_area_id" id="delivery_area_id" class="form-control">
                                            @foreach ($deliveryAreas as $deliveryArea)
                                                <option value="{{ $deliveryArea->id }}">{{ $deliveryArea->name }} - TK {{ $deliveryArea->charge }}</option>
                                            @endforeach
                                        </select>
                                       </div>




                                    <div class="col-12">
                                        <label class="form-label d-block">Payment Method</label>
                                        <div class="d-flex flex-column gap-3">
                                            <label class="border rounded-3 p-3 d-flex justify-content-between align-items-center">
                                                <span>
                                                    <strong>Cash on Delivery</strong>
                                                    <small class="d-block text-muted">Pay when the order is delivered to you.</small>
                                                </span>
                                                <input type="radio"
                                                    wire:model="payment_method"
                                                    name="payment_method"
                                                    value="cod"
                                                    class="form-check-input"
                                                    {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                            </label>

                                            <label class="border rounded-3 p-3 d-flex justify-content-between align-items-center">
                                                <span>
                                                    <strong>bKash</strong>
                                                    <small class="d-block text-muted">Send money first, then submit the transaction ID.</small>
                                                </span>
                                                <input type="radio"
                                                    wire:model="payment_method"
                                                    name="payment_method"
                                                    value="bkash"
                                                    class="form-check-input"
                                                    {{ old('payment_method') === 'bkash' ? 'checked' : '' }}>
                                            </label>
                                        </div>
                                        @error('payment_method')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 {{ old('payment_method') === 'bkash' ? '' : 'd-none' }}"
                                        id="bkash-instructions-card">
                                        <div class="alert alert-warning mb-0">
                                            <h2 class="h6 fw-bold mb-3">bKash Payment Instructions</h2>
                                            <p class="mb-2 d-flex align-items-center gap-2">
                                                <strong>Send money to this bKash number:</strong>
                                                <span id="bkash-number-text">{{ $bkashNumber }}</span>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                    data-copy-bkash-number
                                                    data-number="{{ $bkashNumber }}">
                                                    <span data-copy-label>Copy</span>
                                                </button>
                                            </p>
                                            <ol class="mb-3 ps-3">
                                                <li>Send Money to this number via bKash.</li>
                                                <li>Once the payment is complete, enter the Transaction ID below.</li>
                                                <li>We'll verify the payment and confirm your order.</li>
                                            </ol>

                                            <label for="transaction_id" class="form-label fw-semibold">Transaction ID</label>
                                            <input type="text"
                                                wire:model="transaction_id"
                                                id="transaction_id"
                                                name="transaction_id"
                                                value="{{ old('transaction_id') }}"
                                                class="form-control @error('transaction_id') is-invalid @enderror"
                                                placeholder="Enter your bKash transaction ID">
                                            @error('transaction_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button wire:click="place_order" type="submit" class="btn btn-primary mt-4 w-100" data-place-order-button>
                                    Place Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm sticky-top z-1" style="top: 2rem;">
                        <div class="card-body p-4">
                            <h2 class="h5 mb-3">Order Summary</h2>

                            <div data-checkout-items>
                                @foreach ($cartData->items as $item)
                                    <div class="d-flex gap-3 py-3 border-bottom">
                                        <img src="{{ $item->product->getImageFullUrl() ?? '' }}"
                                            alt="{{ $item->product?->name }}"
                                            width="72"
                                            height="72"
                                            style="width: 72px;"
                                            class="rounded-3 object-fit-cover">
                                        <div class="flex-grow-1">
                                            <h3 class="h6 mb-1">{{ $item->product?->name }}</h3>
                                            <p class="text-muted small mb-1">Qty: {{ $item->quantity }}</p>
                                            <div class="d-flex justify-content-between">
                                                <span class="small text-muted">Unit: ৳ {{ number_format($item->price, 2) }}</span>
                                                <strong>৳ {{ number_format($item->total, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <strong data-checkout-subtotal>৳ {{ number_format($cartData->subTotal(), 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Charge</span>
                                    <strong data-checkout-subtotal>৳ {{ number_format($cartData->delivery_charge, 2) }}</strong>
                                </div>
                                @if($cartData->discount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total</span>
                                    <strong data-checkout-subtotal>৳ {{ number_format($cartData->total, 2) }}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Charge</span>
                                    <strong data-checkout-subtotal>৳ {{ number_format($cartData->discount, 2) }}</strong>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong data-checkout-total>৳ {{ number_format($cartData->grand_total, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            // Check if purchaseEventPayload is available
            @if (isset($initiateCheckoutEventPayload))
                // Push the purchase event payload to the data layer
                console.log('Initiate Checkout event payload:', @json($initiateCheckoutEventPayload));
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push(@json($initiateCheckoutEventPayload));
            @endif
        });

        // Delegated on document so it keeps working after Livewire re-renders
        // the bKash instructions card (e.g. when payment_method changes).
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-copy-bkash-number]');
            if (!btn) return;

            var number = btn.getAttribute('data-number');
            var label = btn.querySelector('[data-copy-label]');

            navigator.clipboard.writeText(number).then(function () {
                if (!label) return;
                var original = label.textContent;
                label.textContent = 'Copied!';
                setTimeout(function () { label.textContent = original; }, 1500);
            });
        });
    </script>
@endpush
