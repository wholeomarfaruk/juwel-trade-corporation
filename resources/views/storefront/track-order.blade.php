@extends('layouts.app')

@section('content')
    <div class="jtc-track">
        <div class="jtc-track__head">
            <h1>Track your order</h1>
            <p>Enter your Order ID and phone number to see the latest status.</p>
        </div>

        @if (session('trackError'))
            <p class="jtc-track__error">{{ session('trackError') }}</p>
        @endif

        <div class="jtc-track-card">
            <h2>① Track by Order ID</h2>
            <form class="jtc-track-form" method="POST" action="{{ route('track.order.lookup') }}">
                @csrf
                <label>Order ID
                    <input type="text" name="order_id" inputmode="numeric" placeholder="e.g. 482" value="{{ old('order_id') }}" required>
                </label>
                <label>Phone number
                    <input type="tel" name="phone" placeholder="01XXXXXXXXX" value="{{ old('phone') }}" required>
                </label>
                <button type="submit" class="jtc-btn jtc-btn--primary jtc-btn--block" style="padding:14px">Track order</button>
            </form>
        </div>

        <div class="jtc-track__divider"><span></span>or<span></span></div>

        @if (auth()->check() && auth()->user()->role === 'user')
            <div class="jtc-track-card jtc-track-card--muted">
                <h2>Already signed in</h2>
                <p>See the full status and history of every order you've placed.</p>
                <a href="{{ route('orders.show') }}" class="jtc-btn jtc-btn--outline jtc-btn--block" style="padding:14px">
                    Check your orders
                </a>
            </div>
        @else
            <div class="jtc-track-card jtc-track-card--muted">
                <h2>Can't find your Order ID?</h2>
                <p>Sign in with your phone number to see all of your orders.</p>
                <button type="button" class="jtc-btn jtc-btn--outline jtc-btn--block" style="padding:14px" @click="openAuthModal({ mode: 'otp' })">
                    Sign in with phone (OTP)
                </button>
            </div>
        @endif
    </div>
@endsection
