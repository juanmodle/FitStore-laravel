<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE payments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER REFERENCES orders(id) ON DELETE RESTRICT,
    vip_subscription_id INTEGER REFERENCES vip_subscriptions(id) ON DELETE RESTRICT,
    reference VARCHAR(100) NOT NULL UNIQUE,
    amount_cents INTEGER NOT NULL CHECK (typeof(amount_cents) = 'integer' AND amount_cents >= 0),
    currency VARCHAR(3) NOT NULL DEFAULT 'EUR' CHECK (length(currency) = 3),
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'paid', 'failed', 'refunded')),
    paid_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME,
    CHECK ((order_id IS NOT NULL AND vip_subscription_id IS NULL) OR (order_id IS NULL AND vip_subscription_id IS NOT NULL)),
    CHECK (status <> 'paid' OR paid_at IS NOT NULL)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
