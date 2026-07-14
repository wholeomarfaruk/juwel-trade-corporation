@extends('layouts.app')

@section('content')
    <div class="jtc-account">
        <nav class="jtc-account__nav">
            <a href="{{ route('account.show') }}">Account</a>
            <a href="{{ route('orders.show') }}" class="is-active">Orders</a>
        </nav>

        <div class="jtc-account__main">
            <div class="jtc-account__head jtc-account__head--row">
                <div>
                    <h1>My orders</h1>
                    <p>Track and review your past orders.</p>
                </div>
                <a href="{{ route('track.order.search') }}" class="jtc-btn jtc-btn--outline" style="padding:11px 20px">Track order</a>
            </div>

            @livewire('website.storefront.orders-page')
        </div>
    </div>
@endsection
