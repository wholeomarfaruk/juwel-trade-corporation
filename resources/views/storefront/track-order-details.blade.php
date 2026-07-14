@extends('layouts.app')

@php
    $steps = [
        'pending'     => 'Order placed',
        'confirmed'   => 'Confirmed',
        'processing'  => 'Processing',
        'in_transit'  => 'In transit',
        'delivered'   => 'Delivered',
    ];
    $stepKeys = array_keys($steps);
    $isCancelled = in_array($order->status, ['cancelled', 'returned']);
    $currentIndex = array_search($order->status, $stepKeys, true);
    if ($currentIndex === false) {
        $currentIndex = 0; // on_hold and any other status still shows "Order placed" as reached
    }
@endphp

@section('content')
    <div class="jtc-track">
        <div class="jtc-track__head">
            <h1>Order #{{ $order->id }}</h1>
            <p>Placed on {{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
        </div>

        <div class="jtc-track-summary">
            <div class="jtc-track-summary__head">
                <div>
                    <div class="jtc-track-summary__id">Order #{{ $order->id }}</div>
                    <div class="jtc-track-summary__date">{{ optional($order->created_at)->format('d M Y, h:i A') }}</div>
                </div>
                <span class="jtc-order-status jtc-order-status--{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
            </div>

            @if ($isCancelled)
                <div class="jtc-track-timeline jtc-track-timeline--cancelled">
                    <div class="jtc-track-step is-cancelled">
                        <span class="jtc-track-step__dot">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </span>
                        <span class="jtc-track-step__label">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            @else
                <div class="jtc-track-timeline">
                    @foreach ($steps as $key => $label)
                        @php $stepIndex = array_search($key, $stepKeys, true); @endphp
                        <div class="jtc-track-step {{ $stepIndex < $currentIndex ? 'is-done' : ($stepIndex === $currentIndex ? 'is-current' : '') }}">
                            <span class="jtc-track-step__dot">
                                @if ($stepIndex < $currentIndex)
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @else
                                    {{ $stepIndex + 1 }}
                                @endif
                            </span>
                            <span class="jtc-track-step__label">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="jtc-track-summary__grid">
                <div>
                    <div class="jtc-track-summary__label">Recipient</div>
                    <div class="jtc-track-summary__value">{{ $order->name }}</div>
                </div>
                <div>
                    <div class="jtc-track-summary__label">Phone</div>
                    <div class="jtc-track-summary__value">{{ $order->phone }}</div>
                </div>
                <div>
                    <div class="jtc-track-summary__label">Delivery area</div>
                    <div class="jtc-track-summary__value">{{ $order->delivery_area?->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="jtc-track-summary__label">Payment</div>
                    <div class="jtc-track-summary__value">{{ str_replace('_', ' ', ucfirst($order->payment_method)) }}</div>
                </div>
                <div>
                    <div class="jtc-track-summary__label">Total</div>
                    <div class="jtc-track-summary__value">৳{{ number_format($order->grand_total ?: $order->total, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="jtc-order-card">
            <div class="jtc-order-card__head">
                <div class="jtc-order-card__id">Items</div>
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

        <div style="text-align:center;margin-top:24px">
            <a href="{{ route('track.order.search') }}" class="jtc-form__link">Track another order</a>
        </div>
    </div>
@endsection
