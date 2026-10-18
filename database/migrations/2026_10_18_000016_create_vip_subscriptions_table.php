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
CREATE TABLE vip_subscriptions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL CHECK (ends_at > starts_at),
    price_cents INTEGER NOT NULL CHECK (typeof(price_cents) = 'integer' AND price_cents >= 0),
    currency VARCHAR(3) NOT NULL DEFAULT 'EUR' CHECK (length(currency) = 3),
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'active', 'expired', 'cancelled')),
    created_at DATETIME,
    updated_at DATETIME
)
SQL);

        Schema::table('vip_subscriptions', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_subscriptions');
    }
};
