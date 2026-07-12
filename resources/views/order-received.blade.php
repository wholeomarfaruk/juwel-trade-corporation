@extends('layouts.app')
@section('segment', $segment)
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        @media (max-width: 992px) {
            .order-info-box {
                padding: 20px 20px;
            }
        }
    </style>
@endpush
@section('content')


    <div class="container">

        <div class="row justify-content-center order-main-box mt-2">
            @if (isset($order) && isset($orderItems) && $orderItems->count() > 0)
                <div class="col-md-8 order-info-box">
                    {{-- <div class="order-logo mt-4">
                    <a class="footer-brand text-decoration-none text-success fw-bolder fs-4" href="#">
                        Juwel Trade Corporation</a>
                </div> --}}
                    <div class="order-text-site mt-5">
                        <h6 class="thanks">Thank you so much!</h6>
                        <h2 class="titel text-start text-success">Order Successful</h2>
                        <p class="order-some-text">
                            We appreciate your order and will begin processing it shortly.
                            We'll be in touch with you soon, so stay tuned.
                        </p>
                    </div>
                    <div class="row date-id mt-4 mb-3">
                        <div class="col">
                            <span class="order-id">Invoice ID:</span>
                            <span class="order-number">{{ $order->id }}</span>
                        </div>
                        <div class="col text-end">
                            <span class="date">Date: </span>
                            <span class="date-time">{{ $order->created_at }}</span>
                        </div>
                    </div>
                    <hr class="m-0">
                    @foreach ($orderItems as $item)
                        <div class="d-flex order-card p-2">
                            <img src="{{ $item->product->getImageThumbUrl() ?? '' }}"
                                alt="" class="me-2" />
                            <div class="">
                                <h5 class="order-product-name">{{ $item->product->name }}</h5>
                                {{-- <p class="text-secondary"> <strong>Code:</strong>  <strong data-p-code="{{ $item->product?->id }}" id="product-code-{{ $item->product?->id }}">{{$item->product?->id }}</strong></p> --}}
                                <p class="text-secondary"> <strong>{{ $item->product?->id }}</strong></p>
                                <p>SKU:{{ $item->product?->id }}</p>

                                {{-- <p class="order-product-weight">{{$item->product->weight}}</p> --}}
                            </div>
                            <p class="order-product-price ms-auto">
                                {{ $item->product->discount_price ?? $item->product->price }} x {{ $item->quantity }} =
                                {{ $item->subtotal }} Tk</p>
                        </div>
                        <hr class="m-0">
                    @endforeach


                    <div class="order-price-unit mt-4">
                        {{-- <div class="d-flex justify-content-between">
                        <p class="delivery-info">Subtotal</p>
                        <p class="delivery-price">{{$order->subtotal}} Tk</p>
                    </div> --}}
                        <div class="d-flex justify-content-between">
                            <p class="delivery-info">Delivery fee - {{ $order->delivery_area->name }}</p>
                            <p class="order-price-unit">{{ $order->fee }} Tk</p>
                        </div>
                        {{-- <div class="d-flex justify-content-between">
                        <p class=" delivery-info">COD Charge {{$order->cod_percentage}}%</p>
                        <p class="order-price-unit">{{$order->cod_charge}} Tk</p>
                    </div> --}}
                        <hr>
                        <div class="d-flex justify-content-between">
                            <p class=" delivery-info-total">Total</p>
                            <p class="order-price-unit-total">{{ $order->total }} Tk</p>
                        </div>
                    </div>
                    {{-- <p class="i-text" style="font-size: 12px;">
                    <i>Please note: as these are fresh products, the price may vary
                        slightly due to differences in weight.</i>
                </p> --}}
                    <hr style="margin: 30px 0px;">
                    <div class="row person-information">
                        <div class="col-md-6">
                            <h5 class="some-titel">Address</h5>
                            <h6 class="person-name">{{ $order->name }}</h6>
                            <p class="order-address">{{ $order->address }} <br>
                                {{ $order->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="some-titel">Delivery</h5>
                            <h6 class="delivery-type">Cash on Delivery</h6>
                            <p class="delivery-notice">Thank you sincerely for your order. Our team will contact you
                                within 24 hours and your order will be processed shortly. Stay with Juwel Trade
                                Corporation.</p>
                        </div>
                    </div>
                    {{-- <div class="buttom-notice mt-5">
                    <p>Join now to get reviews from our valued customers and our exclusive discount offers:</p>
                    <button class="btn btn-order justify-content-center"><i
                            class="fa-solid fa-user-group me-2"></i>Ecoits Facebook Group</button>
                </div> --}}
                </div>
            @else
                <div class="d-flex justify-content-center">
                    <h1 class="text-danger">You have no order</h1>
                </div>
            @endif

        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Check if purchaseEventPayload is available
            @if (isset($purchaseEventPayload))
                // Push the purchase event payload to the data layer
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push(@json($purchaseEventPayload));
            @endif
        });

    </script>
@endpush
