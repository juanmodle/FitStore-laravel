<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Product::class);

        $products = Product::with(['category', 'brand', 'variants'])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->orderBy('name')->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        Gate::authorize('view', $product);

        return view('products.show', ['product' => $product->load(['category', 'brand', 'variants'])]);
    }

    public function manage(): View
    {
        $products = Product::with(['category', 'brand'])->orderBy('name')->paginate(15);

        return view('management.catalog', compact('products'));
    }
}
