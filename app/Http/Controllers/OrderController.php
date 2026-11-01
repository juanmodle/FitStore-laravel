<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Order::class);
        $orders = $request->user()->orders()->orderByDesc('placed_at')->paginate(10);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        Gate::authorize('view', $order);

        return view('account.orders.show', ['order' => $order->load(['items', 'payments'])]);
    }

    public function manage(): View
    {
        Gate::authorize('viewAny', Order::class);
        $orders = Order::with('user')->orderByDesc('placed_at')->paginate(15);

        return view('management.orders', compact('orders'));
    }
}
