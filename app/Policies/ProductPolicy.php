<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        if ($user?->can('catalog.view-drafts')) {
            return true;
        }

        return $product->is_active && (bool) $product->category?->is_active;
    }
}
