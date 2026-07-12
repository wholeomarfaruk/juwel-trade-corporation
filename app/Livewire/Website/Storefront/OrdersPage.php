<?php

namespace App\Livewire\Website\Storefront;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersPage extends Component
{
    use WithPagination;

    public function render()
    {
        $customer = Customer::where('user_id', auth()->id())->first();

        $orders = $customer
            ? $customer->orders()->with('Order_Item.product')->orderByDesc('orders.created_at')->paginate(10)
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

        return view('livewire.website.storefront.orders-page', [
            'orders' => $orders,
        ]);
    }
}
