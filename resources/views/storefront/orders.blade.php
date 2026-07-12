@extends('layouts.app')

@section('content')
    <div class="jtc-account">
        <nav class="jtc-account__nav">
            <a href="{{ route('account.show') }}">Account</a>
            <a href="{{ route('orders.show') }}" class="is-active">Orders</a>
        </nav>

        <div class="jtc-account__main">
            <div class="jtc-account__head">
                <h1>My orders</h1>
                <p>Track and review your past orders.</p>
            </div>

            @livewire('website.storefront.orders-page')
        </div>
    </div>
@endsection
