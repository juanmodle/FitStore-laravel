<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    coupon_id INTEGER REFERENCES coupons(id) ON DELETE RESTRICT,
    number VARCHAR(50) NOT NULL UNIQUE,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'paid', 'shipped', 'completed', 'cancelled')),
    currency VARCHAR(3) NOT NULL DEFAULT 'EUR' CHECK (length(currency) = 3),
    subtotal_cents INTEGER NOT NULL CHECK (typeof(subtotal_cents) = 'integer' AND subtotal_cents >= 0),
    discount_cents INTEGER NOT NULL DEFAULT 0 CHECK (typeof(discount_cents) = 'integer' AND discount_cents >= 0 AND discount_cents <= subtotal_cents),
    shipping_cents INTEGER NOT NULL DEFAULT 0 CHECK (typeof(shipping_cents) = 'integer' AND shipping_cents >= 0),
    tax_cents INTEGER NOT NULL DEFAULT 0 CHECK (typeof(tax_cents) = 'integer' AND tax_cents >= 0),
    total_cents INTEGER NOT NULL CHECK (typeof(total_cents) = 'integer' AND total_cents = subtotal_cents - discount_cents + shipping_cents + tax_cents),
    coupon_code VARCHAR(50),
    customer_name VARCHAR(150) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    shipping_address TEXT NOT NULL CHECK (json_valid(shipping_address)),
    billing_address TEXT NOT NULL CHECK (json_valid(billing_address)),
    placed_at DATETIME NOT NULL,
    created_at DATETIME,
    updated_at DATETIME
)
SQL);

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'placed_at']);
            $table->index(['status', 'placed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
