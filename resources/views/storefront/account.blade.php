@extends('layouts.app')

@section('content')
    <div class="jtc-account">
        <nav class="jtc-account__nav">
            <a href="{{ route('account.show') }}" class="is-active">Account</a>
            <a href="{{ route('orders.show') }}">Orders</a>
        </nav>

        <div class="jtc-account__main">
            <div class="jtc-account__head">
                <h1>My account</h1>
                <p>Manage your profile and saved addresses.</p>
            </div>

            @livewire('website.storefront.account-page')
        </div>
    </div>
@endsection
