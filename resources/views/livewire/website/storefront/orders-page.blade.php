<div>
    <div class="jtc-account-card">
        @if ($orders->isEmpty())
            <p class="jtc-account-empty">You haven't placed any orders yet.</p>
        @else
            <div class="jtc-orders-list">
                @foreach ($orders as $order)
                    <div class="jtc-order-card" wire:key="order-{{ $order->id }}">
                        <div class="jtc-order-card__head">
                            <div>
                                <div class="jtc-order-card__id">Order #{{ $order->id }}</div>
                                <div class="jtc-order-card__date">{{ optional($order->created_at)->format('d M Y, h:i A') }}</div>
                            </div>
                            <span class="jtc-order-status jtc-order-status--{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
                            <div class="jtc-order-card__total">৳{{ number_format($order->grand_total ?: $order->total, 2) }}</div>
                        </div>
                        <div class="jtc-order-card__items">
                            @foreach ($order->Order_Item as $item)
                                <div class="jtc-order-item">
                                    <div class="jtc-order-item__thumb">
                                        <img src="{{ $item->product?->getImageThumbUrl() ?? '' }}" alt="">
                                    </div>
                                    <div class="jtc-order-item__name">{{ $item->product?->name ?? 'Unnamed product' }}</div>
                                    <div class="jtc-order-item__qty">× {{ $item->quantity }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="jtc-pagination" style="margin-top:24px">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
