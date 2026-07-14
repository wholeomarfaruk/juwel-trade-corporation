<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\Phone;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    public function search()
    {
        return view('storefront.track-order');
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'integer'],
            'phone'    => ['required', 'string'],
        ]);

        $phone = Phone::normalizeBd($request->input('phone'));

        $order = $phone
            ? Order::where('id', $request->input('order_id'))->where('phone', $phone)->first()
            : null;

        if (!$order) {
            return redirect()->route('track.order.search')
                ->withInput()
                ->with('trackError', "We couldn't find an order with that ID and phone number. Please double-check and try again.");
        }

        session(['track_order_verified_' . $order->id => true]);

        return redirect()->route('track.order.show', $order->id);
    }

    public function show(Request $request, Order $order)
    {
        if (!$request->session()->get('track_order_verified_' . $order->id, false)) {
            return redirect()->route('track.order.search')
                ->with('trackError', 'Please look up your order with its ID and phone number first.');
        }

        $order->load('Order_Item.product', 'delivery_area');

        return view('storefront.track-order-details', compact('order'));
    }
}
